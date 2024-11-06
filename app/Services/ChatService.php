<?php

namespace App\Services;

use App\Enums\SupportedServices;
use App\Utilities\StreamUtility;
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

    private function getApiKey($service)
    {
        return env(strtoupper($service->value) . '_API_KEY');
    }

    private function getBaseUrl(SupportedServices $service): string
    {
        $urls = [
            SupportedServices::OPENAI->value => 'https://api.openai.com/v1',
            SupportedServices::AVALAI->value => 'https://api.avalapis.ir/v1'
        ];
        return $urls[$service->value] ?? '';
    }
}
