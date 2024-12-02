<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WhatsappService
{
    protected $client;
    protected $apiUrl = 'https://graph.facebook.com/v21.0/432070999999646/messages';
    protected $accessToken = 'EAAPjrm0yxcYBOZBjrbmztT4scWwgMkd1jfqhk8RXjOFeo48cvoCuNZA28uE3BdF2tr44vlmUQTsIOYI9KIq2VedvdmbZAZAzATtg5pPnb6ct1V9qAltXqez8PAde4CukUwwhNzmK43AMciCrZAnPLvJSp6ChnZA8IH2gjsbiBsqG1L2dZARoZCn4qzMVGHUZC0F9JZCMo7GZAyhKaPjo3hAfF7TnNRSW0UZD';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function sendWhatsappMessage($phoneNumber)
    {
        try {
            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'messaging_product' => 'whatsapp',
                    'to' => $phoneNumber,
                    'type' => 'template',
                    'template' => [
                        'name' => 'new_user',
                        'language' => ['code' => 'en'],
                    ],
                ],
            ]);

            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }
    public function sendPromotionMessage($phoneNumber, $userName, $siteLink)
    {
        try {
            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $phoneNumber,
                    'type' => 'template',
                    'template' => [
                        'name' => 'promo_user',
                        'language' => ['code' => 'ro'],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    ['type' => 'text', 'text' => $userName],
                                    ['type' => 'text', 'text' => $siteLink],
                                ],
                            ],
                           
                        ],
                    ],
                ],
            ]);

            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }

}
