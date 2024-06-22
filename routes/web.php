<?php

use Illuminate\Support\Facades\Route;

Route::webhooks('/webhook');

Route::get('/', function () {
    return view('welcome');
});
