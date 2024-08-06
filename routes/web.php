<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::withoutMiddleware([
    VerifyCsrfToken::class,
    ValidateCsrfToken::class,
])->any('/midtrans-callback', [BookingController::class, 'callback'])->name('payment.callback');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');

Route::get('/login', [AuthController::class, 'login'])
    ->name('login')
    ->middleware('guest');
Route::post('/login', [AuthController::class, 'loginPost'])
    ->name('login.post')
    ->middleware('guest');

Route::get('/register', [AuthController::class, 'register'])
    ->name('register')
    ->middleware('guest');
Route::post('/register', [AuthController::class, 'registerPost'])
    ->name('register.post')
    ->middleware('guest');

Route::prefix('schedule')
    ->name('schedule.')
    ->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
    });

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('booking')
        ->name('booking.')
        ->group(function () {
            Route::get('/', [BookingController::class, 'index'])->name('index');
            Route::get('/{id}', [BookingController::class, 'create'])
                ->name('create')
                ->whereNumber('id');
            Route::post('/{id}', [BookingController::class, 'store'])
                ->name('store')
                ->whereNumber('id');
            Route::get('/detail/{id}', [BookingController::class, 'show'])
                ->name('show')
                ->whereNumber('id');

            Route::get('/{id}/delete', [BookingController::class, 'destroy'])
                ->name('destroy')
                ->whereNumber('id');
            Route::get('{id}/approve', [BookingController::class, 'approve'])
                ->name('approve')
                ->whereNumber('id');
            Route::get('{id}/reject', [BookingController::class, 'reject'])
                ->name('reject')
                ->whereNumber('id');

            Route::get('/{id}/pay', [BookingController::class, 'pay'])
                ->name('pay')
                ->whereNumber('id');
        });

    Route::prefix('transaction')
        ->name('transaction.')
        ->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('index');
            Route::get('/{id}', [TransactionController::class, 'show'])
                ->name('show')
                ->whereNumber('id');
            Route::get('/confirm/{id}', [TransactionController::class, 'confirm'])
                ->name('confirm')
                ->whereNumber('id');
        });

    Route::prefix('report')
        ->name('report.')
        ->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
        });

    Route::prefix('catalog')
        ->name('catalog.')
        ->group(function () {
            Route::post('/', [CatalogController::class, 'store'])->name('store');

            Route::post('/{id}', [CatalogController::class, 'update'])
                ->name('update')
                ->whereNumber('id');
            Route::post('/{id}/delete', [CatalogController::class, 'destroy'])
                ->name('destroy')
                ->whereNumber('id');
        });


    Route::prefix('user')
        ->name('user.')
        ->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');

            Route::post('/{id}', [UserController::class, 'update'])
                ->name('update')
                ->whereNumber('id');
            Route::post('/{id}/delete', [UserController::class, 'destroy'])
                ->name('destroy')
                ->whereNumber('id');
        });


});
