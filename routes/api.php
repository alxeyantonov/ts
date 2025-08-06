<?php

use App\Http\Controllers\Api\EmailsController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Support\Facades\Route;


Route::post('users/{user}/send-emails', [UsersController::class, 'sendEmails']);

Route::get('emails', [EmailsController::class, 'index']);
Route::post('emails', [EmailsController::class, 'store']);
Route::get('emails/{email}', [EmailsController::class, 'show']);
Route::put('emails/{email}', [EmailsController::class, 'update']);
Route::delete('emails/{email}', [EmailsController::class, 'destroy']);
