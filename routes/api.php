<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppWebhookController;

use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Support\Facades\Route;



// RUTE WEBHOOK JALUR API 
Route::match(['get', 'post'], '/webhook', [WhatsAppWebhookController::class, 'handle']);
