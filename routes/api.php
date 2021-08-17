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
$user = Auth::user();
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('products',[ProductController::class, 'index'])->name('products.index');
Route::post('products',[ProductController::class, 'store'])->name('products.store');
Route::get('products/{id}',[ProductController::class, 'show'])->name('products.show');
Route::put('products/{id}',[ProductController::class, 'update'])->name('products.update');
Route::delete('products/{id}',[ProductController::class, 'destroy'])->name('products.destroy');
Route::get('products/search/{name}',[ProductController::class, 'search'])->name('products.search');

Route::group(['middleware'=>['auth:sanctum']], function () {
    //Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
