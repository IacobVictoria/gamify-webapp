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
        $this->accessToken = 'EAAJL0c3hggsBOxolZBfeRRijPOJpeyaA2SZBVHmo5pWaagpNm41mdfb68pHZAoHuGzijP43y6hLRZBZCYRmSzfu5y9w0FG2IGuvDPeASYVgWP2iISnPMNqLASUWLBpufuZCEypOXZASQdmF19CqcRYKsrNW3kfSH8XGeEgqMNpiuwdHZBYitp6TYYUlZBypimWw1nXDcg2ycOomtlfq7lOZA7kHY1Mr6EZD';
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