<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Public route
Route::post('register', [AuthController::class, 'register']); // Register
Route::post('login', [AuthController::class, 'login']); // Login


// Private route by sanctum
Route::middleware('auth:sanctum')->group(function ()
{
    Route::resource('products', ProductController::class);
    Route::post('logout', [AuthController::class, 'logout']); // logout
});
