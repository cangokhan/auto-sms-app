<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;



Route::get('/messages/sent', [MessageController::class, 'listSent']);
