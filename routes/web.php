<?php

declare(strict_types=1);

use App\Http\Controllers\FlowController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\VcLoginController;

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

Route::get('/', IndexController::class)->name('index');
Route::get('/flow', [FlowController::class, 'index'])->name('flow');
Route::post('/flow', [FlowController::class, 'retrieveCredential'])
    ->name('flow.retrieve-credential');

// Dynamically register login route based on login_method config
if (config('login_method.method') === 'oidc-vc') {
    Route::middleware(['guest'])->group(function () {
        Route::get('vc/login', [VcLoginController::class, 'login'])->name('vc.login');
        Route::get('vc/login/{sessionId}', [VcLoginController::class, 'session'])
            ->name('vc.login-session')
            ->middleware(['throttle:60,1']);
    });
} else {
    Route::middleware(['guest'])->group(function () {
        Route::get('noop/login', [\App\Http\Controllers\Auth\NoopLoginController::class, 'login'])->name('noop.login');
    });
}

Route::middleware(['auth'])
    ->group(function () {
        Route::post('logout', LogoutController::class)->name('logout');
    });
