<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\SpeechToTextController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/get-response', [ChatController::class, 'getResponse']);
Route::post('/audio-to-text', [SpeechToTextController::class, 'processRequest']);
