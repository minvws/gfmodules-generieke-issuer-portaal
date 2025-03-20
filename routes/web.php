<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\CredentialIssuanceController;
use App\Http\Controllers\FlowController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Auth\DigidMockController;
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

Route::get('/', IndexController::class)->name('index');
Route::get('/flow', [FlowController::class, 'index'])->name('flow');
Route::post('/flow', [FlowController::class, 'retrieveCredential'])
    ->middleware('auth')
    ->name('flow.retrieve-credential');
Route::get('/flow/credential', [FlowController::class, 'editCredentialData'])->name('flow-credential');
Route::post('/flow/credential', [FlowController::class, 'storeCredentialData'])->name('flow-credential.store');

if (config('auth.digid_mock_enabled')) {
    Route::get('oidc/login', [DigidMockController::class, 'login'])->name('oidc.login');
}

Route::middleware(['auth'])
    ->group(function () {
        Route::post('logout', LogoutController::class)->name('logout');
    });
