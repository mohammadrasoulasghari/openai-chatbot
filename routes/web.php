<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\FreepikImageController;
use App\Http\Controllers\SpeechToGenerateController;
use App\Http\Controllers\SpeechToTextController;
use App\Livewire\ChatBot;
use App\Livewire\FreePick\FreePickImageGenerate;
use App\Livewire\SpeechToGenerate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get("/ask", ChatController::class);
Route::post('/speech', [SpeechToTextController::class, 'processRequest']);
Route::post('/convert-audio-to-text', [SpeechToGenerateController::class, 'convertAudioToText']);
Route::get('/ask-gpt', [SpeechToGenerateController::class, 'askGPT']);
Route::get('/speech-to-text', function () {
    return view('speech-to0text');
});
Route::get('/speech-to-generate', SpeechToGenerate::class);
//Route::get('/freepik', [FreepikImageController::class, 'showForm'])->name('freepik.form');
Route::get('/freepik', FreePickImageGenerate::class)->name('freepik.form');

Route::post('/generate-image', [FreepikImageController::class, 'generateImage'])->name('generate.image');
Route::get('/test', ChatBot::class);
