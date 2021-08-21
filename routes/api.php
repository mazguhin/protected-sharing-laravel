<?php

use App\Http\Controllers\Api\RecipientController;
use App\Http\Controllers\Api\RecordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MainController;

Route::get('/', [MainController::class, 'index']);

Route::get('/recipient', [RecipientController::class, 'getAll'])->name('recipient.get-all');
Route::get('/recipient/active', [RecipientController::class, 'getActive'])->name('recipient.get-active');
Route::post('/recipient', [RecipientController::class, 'store'])->name('recipient.store');
Route::post('/recipient/channel', [RecipientController::class, 'attachChannel'])->name('recipient.attach-channel');
Route::put('/recipient/{id}', [RecipientController::class, 'update'])->name('recipient.update');
Route::delete('/recipient/channel', [RecipientController::class, 'detachChannel'])->name('recipient.detach-channel');
Route::delete('/recipient/{id}', [RecipientController::class, 'delete'])->name('recipient.delete');

Route::post('/record', [RecordController::class, 'store'])->name('record.store');
Route::post('/record/{identifier}/accept', [RecordController::class, 'accept'])->name('record.accept');
