<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::view('/', 'chat');

Route::post('/ai-chat', function (Request $request) {
    set_time_limit(180);

    $request->validate([
        'messages' => 'required|array',
    ]);

    $response = Http::timeout(180)->post('http://127.0.0.1:11434/api/chat', [
        'model' => 'llama3.2',
        'messages' => $request->messages,
        'stream' => false,
        'options' => [
            'num_predict' => 150,
        ],
    ]);

    return response()->json([
        'reply' => $response->json('message.content'),
    ]);
});