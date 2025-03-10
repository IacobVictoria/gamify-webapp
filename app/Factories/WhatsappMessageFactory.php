<?php

namespace App\Factories;

use App\Strategies\Whatsapp\MessageTemplateStrategy;
use InvalidArgumentException;

class WhatsappMessageFactory
{
    public static function createMessage(string $messageType, array $data = []): array
    {
        $strategy = MessageTemplateStrategy::getStrategy($messageType);

        if (!$strategy) {
            throw new InvalidArgumentException("Unknown message type: $messageType");
        }

        return $strategy->buildMessage($data);
    }
}
