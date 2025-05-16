<?php

namespace App\Services;

use GuzzleHttp\Client;

class DiscordService
{
    protected $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = config('services.discord.webhook_url');
    }

    public function sendMessage(string $message)
    {
        $client = new Client();

        try {
            $client->post($this->webhookUrl, [
                'json' => [
                    'content' => $message, 
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
        } catch (\Exception $e) {
            logger()->error('Discord webhook error: ' . $e->getMessage());
        }
    }
}
