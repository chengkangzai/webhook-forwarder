<?php

use App\Http\Controllers\WebhookController;
use App\Http\Middlewares\VerifyGreenApiWebhookMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/webhook', WebhookController::class)
    ->middleware(VerifyGreenApiWebhookMiddleware::class);

Route::fallback(function () {
   return redirect()->to(filament()->getUrl());
});
