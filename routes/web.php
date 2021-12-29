<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('payment/history', [PaymentController::class, 'history'])->name('payment.history');
Route::get('payment/history/detail/{year}/{month}', [PaymentController::class, 'detail'])->name('payment.detail');
Route::get('payment/when', [PaymentController::class, 'when'])->name('payment.when');
Route::get('payment/{year}/{month}', [PaymentController::class, 'index'])->name('payment');
Route::post('payment/{year}/{month}/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');
Route::post('payment/{year}/{month}/complete', [PaymentController::class, 'complete'])->name('payment.complete');
