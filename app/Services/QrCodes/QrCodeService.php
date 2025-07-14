<?php

namespace App\Services\QrCodes;

use App\Models\QrCode;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeService
{
    /**
     * Generează un cod QR unic, îl salvează în AWS și în baza de date.
     */
    public function generateQrForProduct($productId)
    {
        $randomNumber = rand(1, 99);
        $code = "{$productId}-{$randomNumber}";

        $qrImage = QrCodeGenerator::format('png')->size(300)->color(0, 0, 0) // Negru
        ->backgroundColor(255, 255, 255) // Alb
        ->margin(1)->generate($code);

        $filePath = "testing/qrcodes_products/{$productId}/{$code}.png";
        Storage::disk('s3')->put($filePath, $qrImage);

        QrCode::create([
            'id' => Uuid::uuid(),
            'code' => $code,
            'product_id' => $productId,
            'image_url' => Storage::disk('s3')->url($filePath),
        ]);
    }

    /**
     * Marchează QR-ul ca folosit și îl șterge din AWS.
     */
    public function invalidateQr(QrCode $qrCode): void
    {
        $productId = $qrCode->product_id;
        Storage::disk('s3')->delete("testing/qrcodes_products/{$productId}/{$qrCode->code}.png");
        $qrCode->delete();
    }
}
