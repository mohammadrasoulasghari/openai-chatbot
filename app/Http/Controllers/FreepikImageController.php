<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            $response = $client->post('https://api.freepik.com/v1/ai/text-to-image', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-freepik-api-key' => $apiKey,
                    'Accept' => 'application/json',
                    'Accept-Language' => 'fa-IR',
                ],
                'json' => [
                    'prompt' => $request->input('prompt'),
                    'negative_prompt' => $request->input('negative_prompt', ''),
                    'num_inference_steps' => $request->input('num_inference_steps', 8),
                    'guidance_scale' => $request->input('guidance_scale', 1),
                    'num_images' => $request->input('num_images', 1),
                    'seed' => $request->input('seed', 42),
                    'image' => [
                        'size' => $request->input('image_size', 'square'),
                    ],
                    'styling' => [
                        'style' => $request->input('style', ''),
                        'color' => 'pastel',
                        'lightning' => $request->input('lightning', ''),
                        'framing' => $request->input('framing', ''),
                    ],
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return response()->json(['images' => $data['data'], 'meta' => $data['meta']]);

        } catch (\Exception $e) {
            Log::error('خطا در تولید تصویر از Freepik API:', [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Failed to generate image.'], 500);
        }
    }
}
