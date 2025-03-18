<?php

namespace App\Http\Controllers;

use App\Interfaces\UserAchievementInterface;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Product;
use App\Models\QrCode;
use App\Models\QrCodeEvent;
use App\Models\QrCodeScan;
use App\Services\Badges\EventBadgeService;
use App\Services\Badges\QrScanProductsBadgeService;
use App\Services\QrCodes\QrCodeService;
use App\Services\UserScoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Faker\Provider\Uuid;

class QrScannerController extends Controller
{
    protected $userScoreService, $badgeService, $qrCodeService, $qrScanProductsBadgeService;

    public function __construct(UserScoreService $userScoreService, EventBadgeService $badgeService, QrCodeService $qrCodeService, QrScanProductsBadgeService $qrScanProductsBadgeService)
    {
        $this->userScoreService = $userScoreService;
        $this->badgeService = $badgeService;
        $this->qrCodeService = $qrCodeService;
        $this->qrScanProductsBadgeService = $qrScanProductsBadgeService;
    }

    public function scanProduct(Request $request)
    {
        $qrCodeValue = $request->input('qrCode');

        $qrCode = QrCode::where('code', $qrCodeValue)->first();

        if (!$qrCode) {
            return back()->with('errorMessage', 'Invalid QR code');
        }

        $product = Product::find($qrCode->product_id);
        $productId = $qrCode->product_id;
        if (!$product) {
            return back()->with('errorMessage', 'Product not found');
        }

        return redirect()->route('products.show', $productId);
    }

    public function scanProductEarnPoints(Request $request)
    {
        $user = Auth()->user();
        $qrCodeValue = $request->input('qrCode');

        $qrCode = QrCode::where('code', $qrCodeValue)->first();

        if (!$qrCode) {
            return back()->with('errorMessage', 'Invalid QR code');
        }

        $product = Product::find($qrCode->product_id);

        $this->userScoreService->addScore($user, $product->score);
        $this->qrScanProductsBadgeService->checkAndAssignBadges($user);

        if (!$product) {
            return back()->with('errorMessage', 'Product not found');
        }

        QrCodeScan::create([
            'id' => Uuid::uuid(),
            'user_id' => $user->id,
            'product_id' => $product->id,
            'scanned_at' => now(),
        ]);
        $this->qrCodeService->invalidateQr($qrCode);
    }

    public function scanEvent(Request $request)
    {
        $qrCodeValue = $request->input('qrCode'); // Valoarea QR scanata
        $eventId = $request->input('eventId'); // ID-ul evenimentului curent

        $qrCode = QrCodeEvent::where('qr_code', $qrCodeValue)->first();

        // evenimentul codului QR se potriveste cu cel curent
        if ($qrCode->event_id != $eventId) {
            return redirect()->back()->with('errorMessage', 'Codul QR nu aparține acestui eveniment.');
        }

        $event = Event::find($qrCode->event_id);
        if (!$event) {
            return redirect()->back()->with('errorMessage', 'Evenimentul nu a fost găsit.');
        }
        // Confirmăm participarea utilizatorului
        $userId = Auth::id();
        $participant = Participant::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();

        if (!$participant) {
            return redirect()->back()->with('errorMessage', 'Nu sunteți înregistrat pentru acest eveniment.');
        }

        $participant->confirmed = true;
        $participant->save();
        $this->userScoreService->addScore($participant->user, 20);
        $this->badgeService->checkAndAssignBadges($participant->user);

        return redirect()->back()->with('message', 'Participation Confirmed! 🎉');
    }
}
