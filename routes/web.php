<?php

declare(strict_types=1);

use App\Http\Controllers\FlowController;
use App\Http\Controllers\IndexController;
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
    ->name('flow.retrieve-credential');
Route::get('/flow/credential', [FlowController::class, 'editCredentialData'])->name('flow-credential');
Route::post('/flow/credential', [FlowController::class, 'storeCredentialData'])->name('flow-credential.store');
