<?php

namespace App\Http\Controllers;

use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class WhatsAppController extends Controller
{
    // public function sendWhatsAppMessage(Request $request)
    // {
    //     $request->validate([
    //         'phoneNumber' => 'required|regex:/^[0-9]{9}$/', 
    //         'message' => 'required|string|max:1000'
    //     ]);
    //     $twilioSid = config('services.twilio.sid');
    //     $twilioAuthToken = config('services.twilio.auth_token');
    //     $twilioWhatsAppNumber = config('services.twilio.whatsapp_number');

    //     if (!$twilioSid || !$twilioAuthToken || !$twilioWhatsAppNumber) {

    //         return response()->json(['error' => 'Twilio credentials are missing'], 500);
    //     }

    //     $recipientNumber = 'whatsapp:+4'.$request->input('phoneNumber'); 

    //     $message = $request->input('message');

    //     $twilio = new Client($twilioSid, $twilioAuthToken);

    //     try {
    //         $twilio->messages->create(
    //             $recipientNumber,
    //             [
    //                 'from' => "whatsapp:$twilioWhatsAppNumber", 
    //                 'body' => $message, 
    //             ]
    //         );

    //         return response()->json(['message' => 'WhatsApp message sent successfully']);
    //     } catch (\Exception $e) {
    //        logger('hei');
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    protected $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function sendPromotion(Request $request)
    {
        $phone = $request->input('phone');
        $name = $request->input('name');
        $link = $request->input('link');

        $response = $this->whatsappService->sendPromotionMessage($phone, $name, $link);

        return response()->json(['message' => 'Mesaj trimis', 'response' => $response]);
    }

}
