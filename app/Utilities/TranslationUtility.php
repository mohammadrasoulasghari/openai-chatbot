<?php

namespace App\Utilities;

use App\Enums\SupportedLanguages;
use App\Enums\SupportedServices;
use App\Services\ChatService;

class TranslationUtility
{
    public static function translate
    (
        $text,
        SupportedLanguages $targetLanguage = SupportedLanguages::ENGLISH,
        SupportedServices $service = SupportedServices::OPENAI
    ): string
    {
        $client =(new ChatService($service))->initializeClient($service);

        $response = $client->chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => "Please translate the following text to {$targetLanguage->value}:"],
                ['role' => 'user', 'content' => $text],
            ],
            'temperature' => 0,
        ]);

        return trim($response->choices[0]->message->content);
    }
}
