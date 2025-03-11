<?php
namespace App\Services\PaymentHandlers;

use App\Factories\PdfGeneratorFactory;
use App\Factories\StorageStrategyFactory;
use App\Jobs\SendMailInvoiceJob;
use App\Models\ClientOrder;
use App\Models\User;

class GenerateInvoiceHandler extends AbstractPaymentHandler
{
    public function handle(ClientOrder $order, array $paymentData): void
    {
        $storageStrategy = StorageStrategyFactory::create('s3');

        $generator = PdfGeneratorFactory::create('client_invoice', $storageStrategy);

        $filename = "invoice_{$order->id}.pdf";
        $pdfUrl = $generator->generatePdf([
            'order' => $order,
            'filename' => $filename
        ]);

        $order->update(['invoice_url' => $pdfUrl]);

        $user = User::findOrFail($order->user_id);
        dispatch(new SendMailInvoiceJob($user, $order, $pdfUrl));

        parent::handle($order, $paymentData);
    }
}

