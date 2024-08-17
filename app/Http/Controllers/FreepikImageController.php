<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FreepikImageController extends Controller
{
    public function showForm()
    {
        return view('freepik');
    }
    public function generateImage(Request $request)
    {
        $client = new Client();
        $apiKey = env('FREEPIK_API_KEY');

        $response = $client->post('https://api.freepik.com/v1/ai/text-to-image', [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-freepik-api-key' => $apiKey,
            ],
            'json' => [
                'prompt' => $request->input('prompt'),
                'negative_prompt' => $request->input('negative_prompt'),
                'num_inference_steps' => $request->input('num_inference_steps', 8),
                'guidance_scale' => $request->input('guidance_scale', 1),
                'num_images' => $request->input('num_images', 1),
                'seed' => $request->input('seed', 42),
                'image' => [
                    'size' => $request->input('image_size', 'square'),
                ],
                'styling' => [
                    'style' => $request->input('style', 'default'),
                    'color' => $request->input('color', 'default'),
                    'lightning' => $request->input('lightning', 'default'),
                    'framing' => $request->input('framing', 'default'),
                ]
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        return view('freepik_result', ['images' => $data['data'], 'meta' => $data['meta']]);
    }
}
