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
        $this->accessToken = 'EAAJL0c3hggsBO2t7ftQLcCRVts0OcmAEo96elETDzjDJgiHZAB0v55QXW6GpS1v78JjZAHNOhvEAjlOZBo7TJPSnwZAZA8PzkgmkD8rutDGgtPigTZCxpO8M01BnLWJHJC2aKEJfcM01KNs64e4GCpOH6NZBG6PQmRwGkPPSZBc6FGtbXJv2tmh6T9TpWH5G0q4dH4mZBEHilkMxcZC1wLLp2LrEoXu0mF';
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