<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use App\Services\NotificationService;
use App\Services\PaymentHandlers\PaymentHandlerInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeController extends Controller
{
    protected $paymentHandler, $notificationService;

    public function __construct(PaymentHandlerInterface $paymentHandler, NotificationService $notificationService)
    {
        $this->paymentHandler = $paymentHandler;
        $this->notificationService = $notificationService;
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
        $order = ClientOrder::findOrFail($request->order_id);

        if ($order) {

            // Rulează Chain of Responsibility pentru confirmarea plății
            $this->paymentHandler->handle($order, []);

            return response()->json(['status' => 'success', 'invoice_url' => $order->invoice_url]);
        }

        return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
    }
    public function cancelPayment(Request $request)
    {
        $user = Auth()->user();
        $order = ClientOrder::findOrFail($request->order_id);
        $order->update(['status' => 'Canceled']); //Marcăm comanda ca anulată

        return response()->json(['message' => 'Payment canceled']);
    }

}
