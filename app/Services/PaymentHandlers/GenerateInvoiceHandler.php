<?php
namespace App\Services\PaymentHandlers;

use App\Factories\PdfGeneratorFactory;
use App\Factories\StorageStrategyFactory;
use App\Jobs\SendMailInvoiceJob;
use App\Models\ClientOrder;
use App\Models\User;
use App\Services\Reports\ClientInvoiceReportService;

class GenerateInvoiceHandler extends AbstractPaymentHandler
{
    protected ClientInvoiceReportService $clientInvoiceReportService;

    public function __construct(ClientInvoiceReportService $clientInvoiceReportService)
    {
        $this->clientInvoiceReportService = $clientInvoiceReportService;
    }
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

        $report = $this->clientInvoiceReportService->createClientInvoiceReport("Comanda client {$order->user_id}", $pdfUrl);
        $order->report_id = $report->id;
        $order->save();

        $user = User::findOrFail($order->user_id);
        dispatch(new SendMailInvoiceJob($user, $order, $pdfUrl));

        parent::handle($order, $paymentData);
    }
}

