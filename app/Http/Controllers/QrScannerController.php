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
use App\Services\UserScoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Faker\Provider\Uuid;

class QrScannerController extends Controller
{
    protected $userScoreService, $badgeService;
    private $achievementInterface;

    public function __construct(UserAchievementInterface $achievementInterface, UserScoreService $userScoreService, EventBadgeService $badgeService)
    {
        $this->achievementInterface = $achievementInterface;
        $this->userScoreService = $userScoreService;
        $this->badgeService = $badgeService;
    }

    public function index()
    {
        return Inertia::render('QrCodes/QrScannerProduct');
    }

    public function scan(Request $request)
    {

        $qrCodeValue = $request->input('qrCode');

        $qrCode = QrCode::where('code', $qrCodeValue)->first();

        if (!$qrCode) {
            return Inertia::render('QrCodes/QrScannerProduct', [
                'error' => 'Invalid QR code'
            ]);
        }
        $product = Product::find($qrCode->product_id);

        if (!$product) {
            return back()->withErrors(['product' => 'Product not found']);
        }


        return redirect()->route('products.show', ['productsId' => $product->id]);
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



    public function updateScore(Request $request)
    {
        $qrCodeValue = $request->input('qrCode');

        $qrCode = QrCode::where('code', $qrCodeValue)->first();

        if (!$qrCode) {
            return Inertia::render('QrCodes/QrScannerProduct', [
                'error' => 'Invalid QR code'
            ]);
        }


        $product = Product::find($qrCode->product_id);

        $productScore = $product->score;

        $user = Auth::user();
        $oldScore = $user->score;
        $newScore = $productScore + $user->score;
        $this->achievementInterface->checkAndSendMedalEmail($user, $newScore, $oldScore);

        $user->update(['score' => $newScore]);

        $qrCodeScan = new QrCodeScan();
        $qrCodeScan->id = Uuid::uuid();
        $qrCodeScan->user_id = $user->id;
        $qrCodeScan->product_id = $product->id;
        $qrCodeScan->scanned_at = now();
        $qrCodeScan->save();

        $imagePath = public_path('images' . DIRECTORY_SEPARATOR . 'qrCodes' . DIRECTORY_SEPARATOR . $product->id . DIRECTORY_SEPARATOR . $qrCode->code . '.png');

        if (file_exists($imagePath)) {
            unlink($imagePath); // Șterge si fișierul imagine
        }

        $qrCode->delete();
        return Inertia::render('QrCodes/QrScannerProduct', [
            'success' => 'QR code deleted and score updated'
        ]);

    }
}
