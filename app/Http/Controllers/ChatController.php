<?php

namespace App\Http\Controllers;

use App\Enums\SupportedServices;
use App\Services\ChatService;
use Illuminate\Http\Request;
use OpenAI;

class ChatController extends Controller
{
    public function __invoke(Request $request)
    {
        $question = $request->query('question');
        $service = SupportedServices::tryFrom($request->query('service')) ?? SupportedServices::OPENAI;

        $chatService = new ChatService($service);
        return $chatService->askQuestionStreamed($question);
    }
}
