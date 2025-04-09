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
        $this->accessToken = 'EAAJL0c3hggsBO23cYqPxr0E2I8dzPeZA1A0Exj01RXKGS3Y51WjREZA8eREOfuk1kh8pb94ZBOXMlyeWcUYrVXCnIi23xHEFCfsZCBnZAmoZAt9AZAd9VSb1QfsBRWuS6sY5cSiL59AMP2cB9ususXZAPaYZBVRspS043nutpIGqpGfbfnQjZBAQZCXe5FIL33gQ5ZAi9UU588R3QYHEG9fEtHOVZB9A0CiYZD';
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