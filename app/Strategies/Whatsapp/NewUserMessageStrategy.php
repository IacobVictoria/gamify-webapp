<?php
namespace App\Strategies\Whatsapp;

use App\Strategies\Whatsapp\MessageTemplateInterface;

class NewUserMessageStrategy implements MessageTemplateInterface
{
    public function buildMessage(array $data): array
    {
        return [
            "messaging_product" => "whatsapp",
            "to" => "40727142462",
            "type" => "template",
            "template" => [
                "name" => "new_register",
                "language" => ["code" => "ro"],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => [
                            [
                                "type" => "text",
                                "parameter_name" => "name",
                                "text" => $data['name']
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
