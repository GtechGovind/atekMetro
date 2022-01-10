<?php

use App\Http\Controllers\Atek\OrderController;
use App\Http\Controllers\Atek\TicketController;
use Illuminate\Support\Facades\Route;

Route::post('new/order', [OrderController::class, 'genOrder']);
Route::post('order/{order_no}', [OrderController::class, 'getOrder']);
Route::post('new/ticket', [TicketController::class, 'genTicket']);
