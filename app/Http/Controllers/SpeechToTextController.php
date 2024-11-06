<?php

namespace App\Http\Controllers;

use App\Enums\SupportedServices;
use App\Helpers\FileHelper;
use App\Services\SpeechService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenAI;

class SpeechToTextController extends Controller
{
    private $speechService;

    public function __construct()
    {
        $this->speechService = new SpeechService();
    }

    public function processRequest(Request $request)
    {
        $file = $request->file('audio');
        $service = SupportedServices::tryFrom($request->input('service')) ?? SupportedServices::OPENAI;

        if (!$file || !$service) {
            return response()->json(['error' => 'Invalid request.'], 400);
        }

        $filePath = FileHelper::storeTemporaryFile($file, 'temp_audio.mp4');

        try {
            $transcription = $this->speechService->convertAudioToTextFromPath(storage_path('app/public/' . $filePath), $service);

            Log::info('Transcription: ' . $transcription);

            return response()->json(['transcription' => $transcription], 200);
        } catch (\Exception $e) {
            Log::error('Exception during audio transcription: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to process audio file.'], 500);
        } finally {
            FileHelper::deleteTemporaryFile($filePath);
        }
    }
}
