<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('register', [AuthController::class, 'register'])->name('auth.register');

Route::middleware(['auth:api'])->group(function () {
    Route::get('user', [AuthController::class, 'me'])->name('auth.user');
    Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
});