<?php

use Illuminate\Support\Facades\Route;

Route::get('telegram-webhook', function () {
    return response()->json(['data' => 'test']);
});
