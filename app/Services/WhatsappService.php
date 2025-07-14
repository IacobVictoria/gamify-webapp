<?php

namespace App\Services;

use App\Factories\WhatsappMessageFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WhatsappService
{

    public function __construct(public Client $client)
    {
    }
    public function sendMessage(string $phoneNumber, string $messageType, array $data = [])
    {
        try {
            $data['phone'] = $phoneNumber;
            $message = WhatsappMessageFactory::createMessage($messageType, $data);

            $response = $this->client->post(env('WHATSAPP_API_URL'), [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('WHATSAPP_ACCESS_TOKEN'),
                    'Content-Type' => 'application/json',
                ],
                'json' => $message

            ]);

            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}