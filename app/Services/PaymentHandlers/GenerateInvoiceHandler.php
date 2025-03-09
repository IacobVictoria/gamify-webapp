<?php
namespace App\Services\PaymentHandlers;

use App\Jobs\SendMailInvoiceJob;
use App\Models\ClientOrder;
use App\Models\User;
use App\Services\DompdfGeneratorService;

class GenerateInvoiceHandler extends AbstractPaymentHandler
{
    protected $pdfGenerator;

    public function __construct(DompdfGeneratorService $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }

    public function handle(ClientOrder $order, array $paymentData): void
    {
        $filename = "invoice_{$order->id}.pdf";
        $pdfUrl = $this->pdfGenerator->generateClientInvoicePdf(['order' => $order], $filename);

        $order->update(['invoice_url' => $pdfUrl]);

        $user = User::findOrFail($order->user_id);
        dispatch(new SendMailInvoiceJob($user, $order, $pdfUrl));

        parent::handle($order, $paymentData);
    }
}

