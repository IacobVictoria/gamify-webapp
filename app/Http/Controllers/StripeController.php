<?php

namespace App\Http\Controllers;

use App\Events\OrderCanceledEvent;
use App\Jobs\ExpediteOrderJob;
use App\Models\ClientOrder;
use App\Services\Badges\ShoppingBadgeService;
use App\Services\DiscountService;
use App\Services\DompdfGeneratorService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeController extends Controller
{
    protected $shoppingBadgeService, $pdfGenerator, $notificationService, $discountService;

    public function __construct(ShoppingBadgeService $shoppingBadgeService, DompdfGeneratorService $pdfGenerator, NotificationService $notificationService, DiscountService $discountService)
    {
        $this->shoppingBadgeService = $shoppingBadgeService;
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
        Stripe::setApiKey(config('services.stripe.secret'));

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

            $this->shoppingBadgeService->checkAndAssignBadges($order->user);

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
