<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;
use Illuminate\Support\Facades\Storage;
class SpeechToGenerateController extends Controller
{
    public function convertAudioToText(Request $request)
    {
        $file = $request->file('audio');
        $path = $file->store('audio', 'public');

        $filePath = storage_path('app/public/' . $path);
        $client = OpenAI::factory()
            ->withApiKey(env('OPENAI_API_KEY'))
            ->make();

        try {
            $response = $client->audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($filePath, 'r'),
                'response_format' => 'verbose_json',
                'language' => 'fa',
            ]);

            Storage::disk('public')->delete($path);

            return response()->json(['transcription' => $response->text ?? '']);
        } catch (\Exception $e) {
            Storage::disk('public')->delete($path);
            return response()->json(['error' => 'Failed to transcribe audio'], 500);
        }
    }

    public function askGPT(Request $request)
    {
        $question = $request->query('question');
        $client = OpenAI::factory()
            ->withApiKey(env('OPENAI_API_KEY'))
            ->make();

        return response()->stream(function () use ($client, $question) {
            $stream = $client->chat()->createStreamed([
                'model' => 'gpt-3.5-turbo',
                'temperature' => 0.8,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $question
                    ]
                ],
                'max_tokens' => 1024,
            ]);

            foreach ($stream as $response) {
                $text = $response->choices[0]->delta->content ?? '';
                if (connection_aborted()) {
                    break;
                }

                echo "event: update\n";
                echo 'data: ' . $text;
                echo "\n\n";
                ob_flush();
                flush();
            }

            echo "event: update\n";
            echo 'data: <END_STREAMING_SSE>';
            echo "\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);
    }
}
