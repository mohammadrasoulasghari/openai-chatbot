<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;

class ChatController extends Controller
{
    public function __invoke(Request $request)
    {
        $question = $request->query('question');
        $service = $request->query('service');

        if ($service === 'openai') {
            $apiKey = env('OPENAI_API_KEY', 'sk-proj-JQUJBt4yEjaeJxJVDcF0T3BlbkFJheiwYA28LJvW4UbFPP3t');
            $baseUrl = 'https://api.openai.com/v1';
        } else {
            $apiKey = env('AVALAI_API_KEY', 'aa-o0RU0Sa7PnZFQqeYigDMjnwbVpSeEzRutfYcgb15yKwqsKdq');
            $baseUrl = 'https://api.avalapis.ir/v1';
        }

        $client = OpenAI::factory()
            ->withApiKey($apiKey)
            ->withBaseUri($baseUrl)
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
