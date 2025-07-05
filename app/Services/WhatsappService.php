<?php

namespace App\Services;

use App\Factories\WhatsappMessageFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WhatsappService
{
    protected Client $client;
    protected string $apiUrl;
    protected string $accessToken;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiUrl = 'https://graph.facebook.com/v22.0/605095459346670/messages';
        $this->accessToken = 'EAAJL0c3hggsBPJZAZA2coyxc7u6HaoIzd3VFKcr2rNqRiuKkvZBHWFZBGUOJFZAZBMhAGsFuvhUHqN1O0RUVYXraJBPBmNy1YADKZBTuqZBwDC1ka4rfejucHqrJ91J8PCUOOCnPoKKxwuT2sqxQBDEVLnsj5usXsMxnxvhrY8prQf06nrpEEdsaily2NaHBXJXHVHrwbc8ZBBXyZBnBUqIkBq9d5mlbDYPFMK2EkPyZBOMpcHnwAZDZD';
    }
    public function sendMessage(string $phoneNumber, string $messageType, array $data = [])
    {
        try {
            $data['phone'] = $phoneNumber;
            $message = WhatsappMessageFactory::createMessage($messageType, $data);

            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
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