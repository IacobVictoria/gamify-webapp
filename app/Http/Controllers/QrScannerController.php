<?php

namespace App\Http\Controllers;

use App\Interfaces\UserAchievementInterface;
use App\Models\Product;
use App\Models\QrCode;
use App\Models\QrCodeScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Faker\Provider\Uuid;

class QrScannerController extends Controller
{
    private $achievementInterface;

    public function __construct(UserAchievementInterface $achievementInterface)
    {
        $this->achievementInterface = $achievementInterface;
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
