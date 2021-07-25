<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MainController;

Route::get('/', [MainController::class, 'index']);
