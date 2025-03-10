<?php
namespace App\Strategies\Whatsapp;

use App\Strategies\Whatsapp\MessageTemplateInterface;

class DeliveredOrderMessageStrategy implements MessageTemplateInterface
{
    public function buildMessage(array $data): array
    {
        return [
            "messaging_product" => "whatsapp",
            "to" => "40727142462",
            "type" => "template",
            "template" => [
                "name" => "order_delivered",
                "language" => ["code" => "ro"],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => [
                            [
                                "type" => "text",
                                "parameter_name" => "name",
                                "text" => $data['name']
                            ],
                            [
                                "type" => "text",
                                "parameter_name" => "order_id",
                                "text" => $data['order_id']
                            ],
                        ]
                    ]
                ]
            ]
        ];
    }
}
