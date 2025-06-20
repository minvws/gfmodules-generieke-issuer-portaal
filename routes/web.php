<?php

declare(strict_types=1);

use App\Http\Controllers\FlowController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\VcLoginController;
use App\Http\Controllers\Auth\MockLoginController;

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
Route::post('/flow/credential', [FlowController::class, 'enrichCredentialData'])->name('flow-credential.enrich');

Route::middleware(['guest'])->group(function () {
    $enabledMethods = config('login_method.enabled_methods', []);
    if (in_array('openid4vp', $enabledMethods, true)) {
        Route::get('vc/login', [VcLoginController::class, 'login'])->name('vc.login');
        Route::get('vc/login/{sessionId}', [VcLoginController::class, 'session'])
            ->name('vc.login-session')
            ->middleware(['throttle:60,1']);
    }
    if (in_array('mock', $enabledMethods, true)) {
        Route::get('mock/login', [MockLoginController::class, 'login'])->name('mock.login');
    }
    if (in_array('oidc', $enabledMethods, true)) {
        Route::get('oidc/login', [\MinVWS\OpenIDConnectLaravel\Http\Controllers\LoginController::class, 'login'])->name('oidc.login');
    }
});

Route::middleware(['auth'])
    ->group(function () {
        Route::post('logout', LogoutController::class)->name('logout');
    });
