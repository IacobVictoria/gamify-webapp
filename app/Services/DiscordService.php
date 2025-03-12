<?php

namespace App\Services;

use GuzzleHttp\Client;

class DiscordService
{
    protected $webhookUrl;

    public function __construct()
    {
        // Adaugă URL-ul webhook-ului Discord
        $this->webhookUrl = config('services.discord.webhook_url');
    }

    public function sendMessage(string $message)
    {
        $client = new Client();

        try {
            $client->post($this->webhookUrl, [
                'json' => [
                    'content' => $message, // Mesajul pe care îl trimiți
                ],
            ]);
        } catch (\Exception $e) {
            logger()->error('Discord webhook error: ' . $e->getMessage());
        }
    }
    public function sendFile(string $message, string $filePath)
    {
        $client = new Client();

        try {
            $client->post($this->webhookUrl, [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen(storage_path("app/{$filePath}"), 'r'),
                        'filename' => basename($filePath),
                    ],
                    [
                        'name' => 'payload_json',
                        'contents' => json_encode([
                            'content' => $message
                        ]),
                    ],
                ]
            ]);

            logger()->info('✅ NPS report sent to Discord successfully!');
        } catch (\Exception $e) {
            logger()->error('❌ Discord webhook error: ' . $e->getMessage());
        }
    }
}
