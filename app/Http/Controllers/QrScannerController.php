<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\QrCode;
use App\Models\QrCodeScan;
use App\Services\Badges\EventBadgeService;
use App\Services\Badges\QrScanProductsBadgeService;
use App\Services\QrCodes\QrCodeService;
use App\Services\UserScoreService;
use Illuminate\Http\Request;
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

        if (!$product) {
            return back()->with('errorMessage', 'Product not found');
        }

        $this->userScoreService->addScore($user, $product->score);
        $this->qrScanProductsBadgeService->checkAndAssignBadges($user);

        QrCodeScan::create([
            'id' => Uuid::uuid(),
            'user_id' => $user->id,
            'product_id' => $product->id,
            'scanned_at' => now(),
        ]);
        $this->qrCodeService->invalidateQr($qrCode);
    }
}
