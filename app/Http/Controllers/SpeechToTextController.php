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
        // دریافت فایل صوتی، سرویس مورد استفاده و زبان
        $file = $request->file('audio');
        $service = $request->input('service');

        // بررسی اولیه درخواست
        if (!$file || !$service) {
            return response()->json(['error' => 'Invalid request.'], 400);
        }

        // حذف فایل موقت قبلی در صورت وجود
        $previousFile = 'temp_audio.mp4';
        if (Storage::disk('public')->exists('audio/' . $previousFile)) {
            Storage::disk('public')->delete('audio/' . $previousFile);
        }

        // ذخیره موقت فایل جدید
        $filename = 'temp_audio.mp4';
        $path = $file->storeAs('audio', $filename, 'public');
        $filePath = storage_path('app/public/' . $path);

        // دریافت API key و Base URL برای سرویس انتخاب شده
        try {
            $apiKey = $this->getApiKeyForService($service);
            $baseUrl = $this->getBaseUrlForService($service);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // ایجاد کلاینت OpenAI
        $client = OpenAI::factory()
            ->withApiKey($apiKey)
            ->withBaseUri($baseUrl)
            ->make();

        try {
            // ارسال فایل برای تبدیل گفتار به متن با مشخص کردن زبان
            $response = $client->audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($filePath, 'r'),
                'response_format' => 'verbose_json',
                'language' => "fa", // اضافه کردن پارامتر زبان
            ]);

            // لاگ کردن پاسخ API برای بررسی‌های بعدی
            Log::info('API Response: ' . json_encode($response));

            // استخراج متن تبدیل شده
            $transcribedText = $response->text ?? '';

            if (empty($transcribedText)) {
                throw new \Exception('متن تشخیص داده نشد. لطفاً دوباره تلاش کنید.');
            }

            // حذف فایل موقت
            Storage::disk('public')->delete($path);

            return response()->json(['transcription' => $transcribedText], 200);

        } catch (\Exception $e) {
            // حذف فایل موقت در صورت بروز خطا
            Storage::disk('public')->delete($path);

            Log::error('Exception during audio transcription: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to process audio file.'], 500);
        }
    }    private function getApiKeyForService($service)
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
