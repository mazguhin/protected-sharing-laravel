<?php

use App\Http\Controllers\Api\RecipientController;
use App\Http\Controllers\Api\RecordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MainController;

Route::get('/', [MainController::class, 'index']);

Route::get('/recipient', [RecipientController::class, 'getAll']);
Route::get('/recipient/active', [RecipientController::class, 'getActive']);
Route::post('/recipient', [RecipientController::class, 'store']);
Route::post('/recipient/channel', [RecipientController::class, 'attachChannel']);
Route::delete('/recipient/channel', [RecipientController::class, 'detachChannel']);

Route::post('/record', [RecordController::class, 'store']);
