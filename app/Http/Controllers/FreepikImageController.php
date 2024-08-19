<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Contracts\Activity;

class FreepikImageController extends Controller
{
    public function showForm()
    {
        return view('freepik');
    }

    public function generateImage(Request $request)
    {
        $client = new Client([
            'verify' => false,
        ]);
        $apiKey = env('FREEPIK_API_KEY');

        try {
            $translatedPrompt = $this->translateToEnglish($request->input('prompt'));
            $negativePrompt = $request->input('negative_prompt', '');
            $translatedNegativePrompt = $negativePrompt ? $this->translateToEnglish($negativePrompt) : '';
            $request_data = [
                'prompt' => $translatedPrompt,
                'negative_prompt' => $translatedNegativePrompt,
                'num_inference_steps' => $request->input('num_inference_steps', 8),
                'guidance_scale' => $request->input('guidance_scale', 1),
                'num_images' => $request->input('num_images', 1),
                'seed' => $request->input('seed', 42),
                'image' => [
                    'size' => $request->input('image_size'),
                ],
                'styling' => [
                    'style' => $request->input('style', ''),
                    'color' => 'pastel',
                    'lightning' => $request->input('lightning', ''),
                    'framing' => $request->input('framing', ''),
                ],
            ];
            $response = $client->post('https://api.freepik.com/v1/ai/text-to-image', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-freepik-api-key' => $apiKey,
                    'Accept' => 'application/json',
                ],
                'json' => $request_data,
            ]);

            $data = json_decode($response->getBody(), true);
            activity()
                ->causedByAnonymous()
                ->withProperties([
                    'service' => 'Freepick',
                    'request_data' => $request_data,
                    'response_data' => $data,
                ])
                ->log('Request and Response for Freepik API');
            return response()->json(['images' => $data['data'], 'meta' => $data['meta']]);

        } catch (\Exception $e) {
            activity()
                ->causedByAnonymous()
                ->withProperties([
                    'service' => 'Freepick',
                    'message' => $e->getMessage(),
                    'response_data' => $e->getTraceAsString(),
                ])
                ->log('Request and Response for Freepik API');
            return response()->json(['error' => 'Failed to generate image.'], 500);
        }
    }

    private function translateToEnglish($text)
    {
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => 'Please translate the following text to English:'],
                    ['role' => 'user', 'content' => $text],
                ],
                'temperature' => 0,
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        activity()
            ->causedByAnonymous()
            ->withProperties([
                'service' => 'ChatGPT',
                'request_data' => $text,
                'response_data' => $data,
            ])
            ->log('Translation request sent to ChatGPT and response received');

        return trim($data['choices'][0]['message']['content']);
    }

}
