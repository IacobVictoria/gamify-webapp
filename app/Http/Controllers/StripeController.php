<?php

namespace App\Http\Controllers;

use App\Events\OrderCanceledEvent;
use App\Jobs\ExpediteOrderJob;
use App\Models\ClientOrder;
use App\Services\BadgeService;
use App\Services\DiscountService;
use App\Services\DompdfGeneratorService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeController extends Controller
{
    protected $badgeService, $pdfGenerator, $notificationService, $discountService;

    public function __construct(BadgeService $badgeService, DompdfGeneratorService $pdfGenerator, NotificationService $notificationService, DiscountService $discountService)
    {
        $this->badgeService = $badgeService;
        $this->pdfGenerator = $pdfGenerator;
        $this->notificationService = $notificationService;
        $this->discountService = $discountService;
    }
    public function index(Request $request)
    {
        $order = ClientOrder::findOrFail($request->order_id);

        return Inertia::render('Stripe', [
            'stripeKey' => config('services.stripe.key'),
            'order' => $order
        ]);
    }
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $order = ClientOrder::findOrFail($request->order_id);

        $paymentIntent = PaymentIntent::create([
            'amount' => $order->total_price * 100, // Stripe folosește cenți (ex: $100.50 → 10050)
            'currency' => 'usd', //only usd
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret
        ]);
    }
    public function confirmPayment(Request $request)
    {
        $user = Auth()->user();
        $order = ClientOrder::findOrFail($request->order_id);

        if ($order) {
            // Marchează comanda ca plătită
            $order->update(['status' => 'Authorized']);

            $filename = "invoice_{$order->id}.pdf";
            $pdfUrl = $this->pdfGenerator->generateClientInvoicePdf(['order' => $order], $filename);

            // Salveaza URL-ul facturii în baza de date
            $order->update(['invoice_url' => $pdfUrl]);

            // Lansăm job-ul pentru expediere
            ExpediteOrderJob::dispatch($order, $user)->delay(now()->addMinutes(1));

            $this->badgeService->shoopingBadges($order->user);

            if ($order->promo_code) {
                $this->discountService->markPromoCodeAsUsed($user, $order->promo_code);
            }

            return response()->json(['status' => 'success', 'invoice_url' => $pdfUrl]);
        }

        return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
    }
    public function cancelPayment(Request $request)
    {
        $user = Auth()->user();
        $order = ClientOrder::findOrFail($request->order_id);
        $order->update(['status' => 'Canceled']); //Marcăm comanda ca anulată

        broadcast(new OrderCanceledEvent($user, $order, $this->notificationService));
        return response()->json(['message' => 'Payment canceled']);
    }

}
