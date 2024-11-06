<?php

namespace App\Utilities;

use Symfony\Component\HttpFoundation\StreamedResponse;

class StreamUtility
{
    public static function streamChatResponse($client, string $question): StreamedResponse
    {
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
