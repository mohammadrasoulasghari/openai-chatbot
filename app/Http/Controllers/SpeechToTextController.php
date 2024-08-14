<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenAI;

class SpeechToTextController extends Controller
{
    /**
     * @throws \Exception
     */
    public function processRequest(Request $request)
    {
        $file = $request->file('audio');
        $service = $request->input('service');

        if (!$file || !$service) {
            return response()->json(['error' => 'Invalid request.'], 400);
        }

        // ذخیره موقت فایل در storage
        $filename = 'temp_audio_' . time() . '.mp4';
        $path = $file->storeAs('audio', $filename, 'public');

        // مسیر کامل فایل ذخیره‌شده
        $filePath = storage_path('app/public/' . $path);

        $apiKey = $this->getApiKeyForService($service);
        $baseUrl = $this->getBaseUrlForService($service);

        $client = OpenAI::factory()
            ->withApiKey($apiKey)
            ->withBaseUri($baseUrl)
            ->make();

        try {
            // ارسال فایل برای تبدیل گفتار به متن
            $response = $client->audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($filePath, 'r'),
                'response_format' => 'verbose_json',
            ]);

            $transcribedText = $response->text ?? '';
              Log::info($transcribedText);
            // حذف فایل موقت
            Storage::disk('public')->delete($path);

            return response()->json(['transcription' => $transcribedText], 200);

        } catch (\Exception $e) {
            // حذف فایل موقت در صورت بروز خطا
            Storage::disk('public')->delete($path);

            Log::error('Exception during audio transcription: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to process audio file.'], 500);
        }
    }

    private function getApiKeyForService($service)
    {
        if ($service === 'openai') {
            return env('OPENAI_API_KEY', 'your-openai-api-key');
        } elseif ($service === 'avalai') {
            return env('AVALAI_API_KEY', 'your-avalai-api-key');
        }

        throw new \Exception('Service not supported.');
    }

    private function getBaseUrlForService($service)
    {
        if ($service === 'openai') {
            return 'https://api.openai.com/v1';
        } elseif ($service === 'avalai') {
            return 'https://api.avalapis.ir/v1';
        }

        throw new \Exception('Service not supported.');
    }
}
