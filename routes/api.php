<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ExpenceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// For user register and login
Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware(['auth:sanctum'])->group(function () {

    // For users listing & logout
    Route::controller(UserController::class)->prefix('user')->group(function () {

        Route::get('/list', 'list');
        Route::post('/logout', 'logout');

    });

    // For expence
    Route::controller(ExpenceController::class)->group(function () {

        Route::get('/user/expence', 'index');

        Route::post('/expences', 'store');

    });

});