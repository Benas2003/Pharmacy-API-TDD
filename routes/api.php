<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsignmentController;
use App\Http\Controllers\ConsignmentProductsController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
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

// Open routes
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/register', [AuthController::class, 'register'])->name('register');


    // Routes with Administrator role
    Route::group(['middleware' => ['role:Administrator']], function () {
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::delete('consignments/{id}', [ConsignmentController::class, 'destroy'])->name('consignments.destroy');
    });


    // Routes with Administrator and Pharmacist role
    Route::group(['middleware' => ['role:Administrator|Pharmacist']], function () {
        Route::put('products/{id}', [ProductController::class, 'update'])->name('products.update');

        Route::put('consignments/{id}', [ConsignmentController::class, 'update'])->name('consignments.update');
    });


    // Routes with Department role
    Route::group(['middleware' => ['role:Department']], function () {
        Route::post('consignments', [ConsignmentController::class, 'store'])->name('consignments.store');
        Route::put('consignments/product/{id}', [ConsignmentProductsController::class, 'update'])->name('consignments.products.update');
    });


    // Routes with all roles
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('products/search/{name}', [ProductController::class, 'search'])->name('products.search');

    Route::get('consignments', [ConsignmentController::class, 'index'])->name('consignments.index');
    Route::get('consignments/{id}', [ConsignmentController::class, 'show'])->name('consignments.show');

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
