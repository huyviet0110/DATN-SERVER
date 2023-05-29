<?php

use App\Http\Controllers\Admins\AdminController;
use App\Http\Controllers\Admins\BusController;
use App\Http\Controllers\Admins\JourneysController;
use App\Http\Controllers\Admins\OrdersController;
use App\Http\Controllers\Admins\TripsController;
use App\Http\Controllers\JourneyController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\Users\Auth\RegisterController;
use App\Http\Controllers\Users\Auth\AuthController;
use App\Http\Controllers\Users\UserController;
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

Route::group([
    'prefix' => 'auth',
], static function ($router) {
    Route::post('/register', [RegisterController::class, 'register'])->middleware('guest')->name('register');
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
    Route::get('/email/verify', [RegisterController::class, 'verificationNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verificationVerify'])->middleware('signed')->name('verification.verify');
});

Route::group([
    'prefix'     => 'users',
    'middleware' => 'auth:api',
], static function ($router) {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'getProfile'])->name('profile');
    Route::post('/cancel-ticket', [UserController::class, 'cancelTicket'])->name('cancel-ticket');
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('update-profile');
    Route::get('/list-orders', [UserController::class, 'getListOrders'])->name('list-orders');
    Route::get('/list-order-trips/{id}', [UserController::class, 'getListOrderTrips'])->name('list-order-trips');
    Route::get('/order-detail/{id}', [UserController::class, 'getOrderDetail'])->name('order-detail');
});

Route::group([
    'prefix'     => 'locations',
    'name'       => 'locations.',
    'controller' => LocationController::class,
], static function () {
    Route::get('/', 'getLocations')->name('index');
});

Route::group([
    'prefix'     => 'journeys',
    'name'       => 'journeys.',
    'controller' => JourneyController::class,
], static function () {
    Route::get('/top-popular', 'getTopPopular')->name('top-popular');
});

Route::group([
    'prefix'     => 'trips',
    'name'       => 'trips.',
    'controller' => TripController::class,
], static function () {
    Route::get('/', 'index')->name('index');
    Route::get('/list-filter', 'getListFilter')->name('list-filter');
    Route::get('/detail', 'getTripDetail')->name('detail');
});

Route::group([
    'prefix'     => 'orders',
    'name'       => 'orders.',
    'controller' => OrderController::class,
], static function () {
    Route::post('/order-trips', 'orderTrips')->name('order-trips');
});

Route::group([
    'prefix' => 'admins',
    'name'   => 'admins.',
], static function () {
    Route::post('/login', [AdminController::class, 'login'])->name('login');

    Route::group(['middleware' => 'auth:admins'], static function () {
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::get('/profile', [AdminController::class, 'getProfile'])->name('profile');
        Route::get('/bus-operators', [AdminController::class, 'getBusOperators'])->name('bus-operators');

        Route::group(['prefix' => 'users'], static function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::post('/update', [UserController::class, 'update'])->name('update');
            Route::post('/destroy', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => 'buses'], static function () {
            Route::get('/', [BusController::class, 'index'])->name('index');
            Route::post('/create', [BusController::class, 'create'])->name('create');
            Route::get('/{id}', [BusController::class, 'show'])->name('show');
            Route::post('/update', [BusController::class, 'update'])->name('update');
            Route::post('/destroy', [BusController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => 'journeys'], static function () {
            Route::get('/', [JourneysController::class, 'index'])->name('index');
            Route::post('/create', [JourneysController::class, 'create'])->name('create');
            Route::get('/{id}', [JourneysController::class, 'show'])->name('show');
            Route::post('/update', [JourneysController::class, 'update'])->name('update');
            Route::post('/destroy', [JourneysController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => 'trips'], static function () {
            Route::get('/', [TripsController::class, 'index'])->name('index');
            Route::post('/create', [TripsController::class, 'create'])->name('create');
            Route::get('/{id}', [TripsController::class, 'show'])->name('show');
            Route::post('/update', [TripsController::class, 'update'])->name('update');
            Route::post('/destroy', [TripsController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => 'orders'], static function () {
            Route::get('/', [OrdersController::class, 'index'])->name('index');
            Route::get('/{id}', [OrdersController::class, 'show'])->name('show');
            Route::post('/update', [OrdersController::class, 'update'])->name('update');
        });
    });
});
