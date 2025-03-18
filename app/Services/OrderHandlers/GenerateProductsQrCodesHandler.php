<?php
namespace App\Services\OrderHandlers;

use App\Jobs\GenerateProductQrCodesJob;
use App\Models\ClientOrder;
use App\Services\QrCodes\QrCodeService;

class GenerateProductsQrCodesHandler extends AbstractOrderHandler
{
    protected QrCodeService $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }
    public function handle(ClientOrder $order, array $validatedData): void
    {
        dispatch(new GenerateProductQrCodesJob($order));
        
        parent::handle($order, $validatedData);
    }
}
