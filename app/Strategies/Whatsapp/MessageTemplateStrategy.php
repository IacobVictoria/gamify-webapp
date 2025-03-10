<?php

namespace App\Strategies\Whatsapp;

use App\Strategies\Whatsapp\MessageTemplateInterface;

class MessageTemplateStrategy
{
    public static function getStrategy(string $messageType): ?MessageTemplateInterface
    {
        return match ($messageType) {
            'new_user' => new NewUserMessageStrategy(),
            'order_confirmed' => new ConfirmedOrderMessageStrategy(),
            'order_expedited' => new ExpeditedOrderMessageStrategy(),
            'order_delivered' => new DeliveredOrderMessageStrategy(),
            default => null,
        };
    }
}
