<?php

use App\Http\Controllers\WEB\WebAuthController;
use App\Http\Controllers\WEB\WebConsignmentController;
use App\Http\Controllers\WEB\WebConsignmentProductsController;
use App\Http\Controllers\WEB\WebOrderController;
use App\Http\Controllers\WEB\WebProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
