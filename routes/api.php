<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
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
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware'=>['auth:sanctum']], function () {

    //Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Products' routes with Admin role
    Route::group(['middleware' => ['role:Admin']], function () {
        Route::post('products',[ProductController::class, 'store'])->name('products.store');
        Route::delete('products/{id}',[ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Products' routes with Admin and Pharmacist role
    Route::group(['middleware' => ['role:Admin|Pharmacist']], function () {
        Route::put('products/{id}',[ProductController::class, 'update'])->name('products.update');
    });

    Route::get('products',[ProductController::class, 'index'])->name('products.index');
    Route::get('products/{id}',[ProductController::class, 'show'])->name('products.show');
    Route::get('products/search/{name}',[ProductController::class, 'search'])->name('products.search');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
