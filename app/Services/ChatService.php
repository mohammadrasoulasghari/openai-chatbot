<?php

namespace App\Services;

use App\Enums\SupportedServices;
use App\Utilities\StreamUtility;
use Illuminate\Support\Facades\Config;
use OpenAI;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatService
{
    private $client;

    public function __construct(SupportedServices $service = SupportedServices::OPENAI)
    {
        $this->client = $this->initializeClient($service);
    }

    public function initializeClient($service)
    {
        $apiKey = $this->getApiKey($service);
        $baseUrl = $this->getBaseUrl($service);

        return OpenAI::factory()
            ->withApiKey($apiKey)
            ->withBaseUri($baseUrl)
            ->make();
    }

    public function askQuestionStreamed(string $question): StreamedResponse
    {
        return StreamUtility::streamChatResponse($this->client, $question);
    }

    private function getApiKey(SupportedServices $service)
    {
        return match ($service) {
            SupportedServices::OPENAI => Config::get('openai.api_key'),
            SupportedServices::AVALAI => Config::get('avalai.api_key'),
        };
    }

    private function getBaseUrl(SupportedServices $service): string
    {
        return match ($service) {
            SupportedServices::OPENAI => Config::get('openai.base_url'),
            SupportedServices::AVALAI => Config::get('avalai.base_url'),
        };
    }
}
