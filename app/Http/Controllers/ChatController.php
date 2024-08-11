<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{

    //with laravel open ai package

    public function __invoke(Request $request)
    {
        $question = $request->query('question');
        return response()->stream(function () use ($question) {
            $stream = OpenAI::chat()->createStreamed([
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
                $text = $response->choices[0]->delta->content;
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







    //with guzzle http
//    public function __invoke(Request $request)
//    {
//        $question = $request->query('question');
//
//        return response()->stream(function () use ($question) {
//            $client = new Client();
//            $apiKey = env('OPENAI_API_KEY'); // بازیابی کلید API از محیط
//
//            try {
//                $response = $client->post('https://api.openai.com/v1/chat/completions', [
//                    'headers' => [
//                        'Authorization' => 'Bearer ' . $apiKey, // استفاده از کلید API
//                        'Content-Type' => 'application/json',
//                    ],
//                    'json' => [
//                        'model' => 'gpt-3.5-turbo',
//                        'temperature' => 0.8,
//                        'messages' => [
//                            [
//                                'role' => 'user',
//                                'content' => $question,
//                            ]
//                        ],
//                        'max_tokens' => 1024,
//                        'stream' => true,
//                    ],
//                    'stream' => true,
//                ]);
//
//                $body = $response->getBody();
//
//                while (!$body->eof()) {
//                    $chunk = $body->read(1024); // خواندن بخش‌های ۱۰۲۴ بایتی از جریان
//                    $lines = explode("\n", $chunk); // تقسیم متن به خطوط
//
//                    foreach ($lines as $line) {
//                        $line = trim($line);
//                        if (!empty($line)) {
//                            Log::info("Received line: " . $line); // لاگ دریافت خط
//
//                            $decoded = json_decode($line);
//                            if (isset($decoded->choices[0]->delta->content)) {
//                                $text = $decoded->choices[0]->delta->content;
//
//                                Log::info("Extracted text: " . $text); // لاگ متن استخراج شده
//
//                                if (connection_aborted()) {
//                                    break;
//                                }
//
//                                echo "event: update\n";
//                                echo 'data: ' . $text;
//                                echo "\n\n";
//                                ob_flush();
//                                flush();
//                            }
//                        }
//                    }
//                }
//            } catch (\Exception $e) {
//                Log::error('Error in streaming response: ' . $e->getMessage());
//            }
//
//            echo "event: update\n";
//            echo 'data: <END_STREAMING_SSE>';
//            echo "\n\n";
//            ob_flush();
//            flush();
//        }, 200, [
//            'Cache-Control' => 'no-cache',
//            'X-Accel-Buffering' => 'no',
//            'Content-Type' => 'text/event-stream',
//        ]);
//    }
}
