<?php

namespace App\Strategies\Whatsapp;

interface MessageTemplateInterface
{
    public function buildMessage(array $data): array;
}
