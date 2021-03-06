<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MyPageController;

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

Route::prefix('category')->group(function () {
    Route::get('create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('create', [CategoryController::class, 'createPost'])->name('category.createPost');
    Route::put('update', [CategoryController::class, 'update'])->name('category.update');
    Route::get('show', [CategoryController::class, 'show'])->name('category.show');
});

Route::prefix('payment')->group(function () {
    Route::get('/history', [PaymentController::class, 'history'])->name('payment.history');
    Route::get('/history/category/{categoryId}', [PaymentController::class, 'historyByCategory'])->name('payment.history.category');
    Route::get('/history/graph', [PaymentController::class, 'graph'])->name('payment.history.graph');
    Route::get('/history/graph/category', [PaymentController::class, 'graphByCategory'])->name('payment.history.graph.category');
    Route::get('/history/detail/{year}/{month}', [PaymentController::class, 'detail'])->name('payment.detail');
    Route::get('/notification/{year}/{month}', [PaymentController::class, 'notification'])->name('payment.notification');
    Route::post('/notification/{year}/{month}', [PaymentController::class, 'notificationPost'])->name('payment.notificationPost');
    Route::get('/when', [PaymentController::class, 'when'])->name('payment.when');
    Route::get('/{year}/{month}', [PaymentController::class, 'index'])->name('payment');
    Route::post('/{year}/{month}/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');
    Route::post('/{year}/{month}/complete', [PaymentController::class, 'complete'])->name('payment.complete');
});

Route::prefix('mypage')->group(function () {
    Route::get('/', [MyPageController::class, 'index'])->name('mypage');
    Route::get('/user', [MyPageController::class, 'user'])->name('mypage.user');
    Route::post('/user_edit', [MyPageController::class, 'userEdit'])->name('mypage.userEdit');
    Route::get('/user_show', [MyPageController::class, 'userShow'])->name('mypage.userShow');
    Route::get('/password_change', [MyPageController::class, 'getPasswordChange'])->name('mypage.getPasswordChange');
    Route::post('/password_change', [MyPageController::class, 'postPasswordChange'])->name('mypage.postPasswordChange');
});
