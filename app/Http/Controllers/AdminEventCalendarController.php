<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessRecurringEventJob;
use App\Models\Event;
use App\Models\Product;
use App\Services\EventService;
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

    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index()
    {
        $events = $this->eventService->getAllEvents();
        $categories = Product::distinct()->pluck('category');
        $suppliers = Supplier::all();
        $productsBySupplier = SupplierProduct::with('supplier')->get()->groupBy('supplier_id');

        $parentEvents = $this->eventService->getRecurringEvents();
        $lastEvents = $this->eventService->getLastEvents($parentEvents);
        $ghostEvents = $this->eventService->generateGhostEvents($lastEvents);
        $events = $events->map(function ($event) use ($lastEvents) {
            $eventArray = $event->toArray();
            $eventArray['is_last_recurring'] = $lastEvents->contains('id', $event->id);
            $eventArray['is_started'] = now()->greaterThanOrEqualTo($event->start);
            return $eventArray;
        });

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
            'events' => array_merge($events->toArray(), $ghostEvents->toArray()),
            'categories' => $categories,
            'suppliers' => $suppliers,
            'products' => $productsBySupplier->toArray(),
            'favoritesDiscounts' => $favoritesDiscounts,
            'favoritesCommands' => $favoritesCommands
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
            'is_published' => 'boolean',
            'is_recurring' => 'nullable|boolean',
            'recurring_interval' => 'nullable|string|in:daily,weekly,monthly',
        ]);

        $validated['is_recurring'] = $validated['is_recurring'] ?? false;
        $validated['recurring_interval'] = $validated['recurring_interval'] ?? null;

        $nextOccurrence = null;
        if (!empty($validated['is_recurring']) && !empty($validated['recurring_interval'])) {
            $nextOccurrence = match ($validated['recurring_interval']) {
                'daily' => Carbon::parse($validated['start'])->addDay(),
                'weekly' => Carbon::parse($validated['start'])->addWeek(),
                'monthly' => Carbon::parse($validated['start'])->addMonth(),
                default => null,
            };
        }

        $event = new Event([
            'id' => Uuid::uuid(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start' => $validated['start'],
            'end' => $validated['end'],
            'status' => $validated['status'] ?? 'OPEN',
            'type' => $validated['type'],
            'details' => $validated['details'] ?? null,
            'calendarId' => $validated['calendarId'],
            'is_published' => $validated['is_published'] ?? false,
            'is_recurring' => $validated['is_recurring'] ?? false,
            'recurring_interval' => $validated['is_recurring'] ? $validated['recurring_interval'] : null,
            'next_occurrence' => $nextOccurrence,
            'user_id' => Auth()->user()->id,
        ]);
        $event->last_recurring_event_id = $validated['is_recurring'] ? $event->id : null;

        $event->save();

        // Lansează job-ul doar dacă evenimentul este recurent
        if ($event->is_recurring) {
            ProcessRecurringEventJob::dispatch($event)->delay($event->next_occurrence);
        }


        return redirect()->back();
    }

    public function stopRecurrence($id)
    {
        $event = Event::findOrFail($id);

        $parentEvent = Event::where('id', $event->parent_event_id ?? $event->id)->first();

        if ($parentEvent) {
            $parentEvent->update([
                'is_recurring' => false,
                'last_recurring_event_id' => null,
                'next_occurrence' => null
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
            'payload.is_published' => 'boolean',
            'payload.is_recurring' => 'boolean',
            'payload.recurring_interval' => 'nullable|string|in:daily,weekly,monthly',
            'payload.details' => 'nullable|json',
        ]);

        $payload = $request->input('payload');
        $event->title = $payload['title'];
        $event->description = $payload['description'];
        $event->start = $payload['start'];
        $event->end = $payload['end'];
        $event->details = $payload['details'];
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

        // Dacă evenimentul NU era recurent și devine recurent, activăm recurența
        if (!$event->is_recurring && $payload['is_recurring']) {
            $nextOccurrence = match ($payload['recurring_interval']) {
                'daily' => Carbon::parse($payload['start'])->addDay(),
                'weekly' => Carbon::parse($payload['start'])->addWeek(),
                'monthly' => Carbon::parse($payload['start'])->addMonth(),
                default => null,
            };

            $event->is_recurring = true;
            $event->recurring_interval = $payload['recurring_interval'];
            $event->next_occurrence = $nextOccurrence;

            // Setăm `last_recurring_event_id` în PĂRINTE (dacă există)
            if ($event->parent_event_id === null) {
                $event->last_recurring_event_id = $event->id;
            } else {
                $parentEvent = Event::where('id', $event->parent_event_id)->first();
                if ($parentEvent) {
                    $parentEvent->update(['last_recurring_event_id' => $event->id]);
                }
            }

            // Lansăm job-ul doar dacă evenimentul a devenit recurent
            ProcessRecurringEventJob::dispatch($event)->delay($event->next_occurrence);
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
        }

        if ($event->is_recurring) {
            $familyId = $event->parent_event_id ?? $event->id;

            // Oprire recurență pentru TOATE evenimentele din acea familie
            Event::where('parent_event_id', $familyId)
                ->orWhere('id', $familyId)
                ->update([
                    'is_recurring' => false,
                    'last_recurring_event_id' => null,
                    'next_occurrence' => null
                ]);
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
