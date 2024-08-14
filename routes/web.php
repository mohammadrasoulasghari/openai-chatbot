<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\SpeechToTextController;
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
Route::get('/speech-to-text', function () {
    return view('speech-to0text'); // این مسیر فایل resources/views/chat.blade.php را برمی‌گرداند
});
