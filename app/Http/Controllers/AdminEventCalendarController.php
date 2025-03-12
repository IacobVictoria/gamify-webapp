<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Product;
use App\Models\QrCodeEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminEventCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();
        $categories = Product::distinct()->pluck('category');
        $suppliers = Supplier::all();
        $productsBySupplier = SupplierProduct::with('supplier')
            ->get()
            ->groupBy('supplier_id');

        $favoritesCommands = Event::where('type', 'supplier_order')
            ->where('is_favorite', 1)
            ->select('id', 'title', 'description', 'details')
            ->get()
            ->map(function ($event) {
                $event->details = json_decode($event->details, true);
                return $event;
            });
            
        $favoritesDiscounts = Event::where('type', 'discount')
            ->where('is_favorite', 1)
            ->select('id', 'title', 'description', 'details')
            ->get()
            ->map(function ($event) {
                $event->details = json_decode($event->details, true);
                return $event;
            });

        return Inertia::render('Admin/Calendar/Index', [
            'events' => $events,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'products' => $productsBySupplier->toArray(),
            'favoritesCommands' => $favoritesCommands->toArray(),
            'favoritesDiscounts' => $favoritesDiscounts->toArray()
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|string',
            'end' => 'required|string',
            'status' => 'nullable|in:CLOSED,OPEN',
            'type' => 'nullable|string',
            'details' => 'nullable|json',
            'calendarId' => 'string',
            'is_published' => 'boolean'
        ]);

        $event = new Event([
            'id' => Uuid::uuid(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start' => $validated['start'],
            'end' => $validated['end'],
            'status' => $validated['status'] ?? 'OPEN',
            'type' => $validated['type'] ?? 'event',
            'details' => $validated['details'] ?? null,
            'calendarId' => $validated['calendarId'],
            'is_published' => $validated['is_published'] ?? false,
        ]);

        $event->save();
        if ($event->type === 'event') {
            $qrFileName = "events/qr_codes_{$event->id}.png";
            //url ul evenimentului
            $qrCodeImage = QrCodeGenerator::format('png')
                ->size(300)
                ->color(0, 0, 0) // Negru
                ->backgroundColor(255, 255, 255) // Alb
                ->margin(1)
                ->generate($qrFileName);

            Storage::disk('s3')->put($qrFileName, $qrCodeImage, 'public');
            QrCodeEvent::create([
                'id' => Uuid::uuid(),
                'qr_code' => $qrFileName,
                'image_url' => Storage::disk('s3')->url($qrFileName),
                'event_id' => $event->id,
            ]);

        }
        return redirect()->back();

    }

    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'payload.title' => 'required|string|max:255',
            'payload.description' => 'required|string|max:500',
            'payload.start' => 'required|date',
            'payload.end' => 'required|date|after:payload.start',
            'payload.is_published' => 'boolean'
        ]);
        $payload = $request->input('payload');

        $event->title = $payload['title'];
        $event->description = $payload['description'];
        $event->start = $payload['start'];
        $event->end = $payload['end'];
        $event->is_published = $payload['is_published'] ?? false;
        if (isset($payload['status'])) {
            $event->status = $payload['status'];
        } else {
            $now = now();
            if (Carbon::parse($payload['end'])->gt($now)) {
                $event->status = 'OPEN'; // Dacă end time este mai mare decât data curentă, setăm statusul ca OPEN
            } else {
                $event->status = 'CLOSED'; // Dacă end time a expirat, setăm statusul ca CLOSED
            }
        }

        $event->save();

        return redirect()->back();
    }

    public function updateFavorites(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'is_favorite' => 'boolean'
        ]);

        if (isset($validated['is_favorite'])) {
            $event->is_favorite = $validated['is_favorite'];
        }

        $event->save();

        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $event = Event::find($id);
        if ($event->type === 'discount') { //daca stergem un discount sa refacem preturile
            $this->resetDiscountPricesOnEventDeletion($event);
        } elseif ($event->type === 'event') {
            $qrCode = QrCodeEvent::where('event_id', $id)->first();

            if ($qrCode) {
                Storage::disk('s3')->delete($qrCode->qr_code);
                QrCodeEvent::where('event_id', $id)->delete();
            }
        }

        $event->delete();
        return redirect()->back();
    }
    private function resetDiscountPricesOnEventDeletion(Event $event)
    {
        $details = json_decode($event->details, true); // Convertim JSON-ul într-un array asociativ

        $productsQuery = match ($details['applyTo'] ?? '') { // Verificăm dacă 'applyTo' există
            'all' => Product::all(),
            'categories' => Product::where('category', $details['category'] ?? '')->get(),
            default => collect(),
        };

        // Iterăm direct prin colecția de produse
        foreach ($productsQuery as $product) {
            $this->removeDiscountAndRecalculate($product, $event->id);
        }

        // Eliminăm cache-ul specific acestui eveniment
        Cache::forget("discount_emitted_{$event->id}");
    }

    private function removeDiscountAndRecalculate(Product $product, $eventId)
    {
        // Obținem reducerile existente
        $discounts = Cache::get("discount_product_{$product->id}", []);

        // Filtrăm discount-ul care trebuie eliminat
        $remainingDiscounts = array_filter($discounts, fn($d) => $d['event_id'] !== $eventId);

        if (count($remainingDiscounts) < count($discounts)) {
            // Recalculăm prețul după eliminarea reducerii
            $product->price = $product->old_price ?? $product->price;

            foreach ($remainingDiscounts as $discount) {
                $product->price *= (1 - $discount['discount'] / 100);
            }

            $product->price = round($product->price, 2);

            // Actualizăm cache-ul
            if (!empty($remainingDiscounts)) {
                Cache::put("discount_product_{$product->id}", array_values($remainingDiscounts));
            } else {
                // Dacă nu mai sunt reduceri, resetăm complet produsul
                $product->price = $product->old_price;
                $product->old_price = null;
                Cache::forget("discount_product_{$product->id}");
            }

            $product->save();
        }
    }




}
