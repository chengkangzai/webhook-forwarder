<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhook', WebhookController::class);

Route::fallback(function () {
    return redirect()->to(filament()->getUrl());
});
