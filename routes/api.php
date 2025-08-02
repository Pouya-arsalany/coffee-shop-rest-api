<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------|
| Public API Routes
|--------------------------------------------------------------------------|
*/

// Menu related (public)
Route::prefix('menu')->name('menu.')->controller(MenuController::class)->group(function () {
    Route::get('/category/{id}', 'filterByCategory')->name('filter');
});
Route::get('categories/search', [CategoryController::class, 'search'])->name('categories.search');
Route::get('products/search', [ProductController::class, 'search'])->name('products.search');

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('tables', TableController::class)->only(['index', 'show']);

/*
|--------------------------------------------------------------------------|
| Authenticated API Routes
|--------------------------------------------------------------------------|
*/
//for if the user is ADMIN
Route::middleware(['user_role'])->group(function () {
Route::get('orders/admin', [OrderController::class, 'adminIndex'])->name('orders.admin');
Route::apiResource('categories', CategoryController::class)->only(['store', 'destroy', 'update']);
Route::apiResource('products', ProductController::class)->only(['store', 'destroy', 'update']);
Route::apiResource('tables', TableController::class)->only(['store', 'destroy', 'update']);
});


//for if the user is LOGGED IN
Route::middleware(['login_status'])->group(function () {

    // Reservation-specific order routes
    Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'destroy','show']);
    Route::prefix('reservation')->name('reservation.')->group(function () {
        Route::post('/add/{product}', [OrderController::class, 'store'])->name('add');
        Route::put('/item/{id}', [OrderController::class, 'removeItem'])->name('removeItem');
        Route::get('/select-table', [OrderController::class, 'showTableSelection'])->name('selectTable');
        Route::post('/choose-table', [OrderController::class, 'chooseTable'])->name('chooseTable');
        Route::post('/submit', [OrderController::class, 'submitOrder'])->name('submit');
        Route::delete('/clear', [OrderController::class, 'clearOrder'])->name('clear');
    });
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');


});
