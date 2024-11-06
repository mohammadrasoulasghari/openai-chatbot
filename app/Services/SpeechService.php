<?php

namespace App\Services;

use App\Enums\SupportedServices;
use Illuminate\Support\Facades\Storage;

class SpeechService
{
    public function convertAudioToTextFromPath(string $filePath, SupportedServices $service, $language = 'fa'): string
    {
        $client = (new ChatService($service))->initializeClient($service);
        $response = $client->audio()->transcribe([
            'model' => 'whisper-1',
            'file' => fopen($filePath, 'r'),
            'response_format' => 'verbose_json',
            'language' => $language,
        ]);

        return $response->text ?? '';
    }
}
