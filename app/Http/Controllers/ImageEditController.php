<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use OpenAI;
use Illuminate\Support\Facades\Log;

class ImageEditController extends Controller
{
    public function edit(Request $request)
    {
        $client = new Client([
            'verify' => false,
        ]);

        try {
            $imageBase64 = $request->input('image'); // Assuming image is sent as a Base64 string
            $prompt = $request->input('prompt');

            // تبدیل Base64 به فایل موقت
            $image = base64_decode($imageBase64);
            $tempImagePath = tempnam(sys_get_temp_dir(), 'image_') . '.png';
            file_put_contents($tempImagePath, $image);

            // Log the file path
            Log::info('Temporary image path: ' . $tempImagePath);
            $response = $client->post('https://api.openai.com/v1/images/edits', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                ],
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => $image,
                        'filename' => 'image.png'
                    ],
                    [
                        'name' => 'prompt',
                        'contents' => $prompt
                    ],
                    [
                        'name' => 'size',
                        'contents' => '1024x1024'
                    ],
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json(['images' => $data['data']]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to edit image.'], 500);
        }
    }

}
