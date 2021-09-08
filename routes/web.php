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
    return redirect('/login');
});
Route::get('/login', function () {
    return view('login');
})->name('website.login');

Route::post('/sign_in', [WebAuthController::class, 'login'])->name('login.web');

Route::group(['middleware' => ['auth:web']], function() {

    Route::get('/panel', function () {
        return view('panel');
    });

    Route::post('/order/{id}/{amount}', [WebProductController::class, 'stockUpdate']);
    Route::post('/search/{code}', [WebProductController::class, 'search']);
    Route::get('/order/search/{unique}', [WebOrderController::class, 'orderSearch']);
    Route::get('/consignment/{id}', [WebConsignmentController::class, 'show']);
    Route::get('/consignments/{filter}', [WebConsignmentController::class, 'index']);

    Route::put('/consignment-update', [WebConsignmentProductsController::class, 'update'])->name('web.update.custom.consignment');

    Route::delete('/consignment/{id}', [WebConsignmentController::class, 'destroy']);
    Route::put('/consignment/{id}', [WebConsignmentController::class, 'update'])->name('web.update.consignment');
    Route::post('/consignment', [WebConsignmentController::class, 'store'])->name('web.create.consignment');



    Route::group(['middleware' => ['role:Department']], function () {
        Route::get('/create-consignment', function () {
            return view('create_consignment');
        });
        Route::get('/department-consignment', function () {
            return view('department_consignment');
        });
    });

    Route::group(['middleware' => ['role:Administrator']], function () {
        Route::get('/consignments-table', function () {
            return view('consignments_table');
        });
    });

    Route::group(['middleware' => ['role:Pharmacist']], function () {

        Route::get('/consignments-action', function () {
            return view('consignments_actions');
        });
        Route::get('/stock-update', function () {
            return view('stock_update');
        });
    });

    Route::get('/logout', [WebAuthController::class, 'logout'])->name('logout.web');
});
