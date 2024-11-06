<?php

namespace App\Http\Controllers;

use App\Enums\SupportedServices;
use App\Services\ChatService;
use App\Services\SpeechService;
use Illuminate\Http\Request;
use OpenAI;
use Illuminate\Support\Facades\Storage;
class SpeechToGenerateController extends Controller
{
    private SpeechService $speechService;
    private ChatService $chatService;

    public function __construct()
    {
        $this->speechService = new SpeechService();
        $this->chatService = new ChatService(SupportedServices::OPENAI);
    }

    public function convertAudioToText(Request $request)
    {
        $file = $request->file('audio');
        $service = SupportedServices::tryFrom($request->input('service')) ?? SupportedServices::OPENAI;

        try {
            $transcription = $this->speechService->convertAudioToText($file, $service);
            return response()->json(['transcription' => $transcription]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to transcribe audio'], 500);
        }
    }

    public function askGPT(Request $request)
    {
        $question = $request->query('question');
        $response = $this->chatService->askQuestionStreamed($question);

        return response()->json(['response' => $response]);
    }
}
