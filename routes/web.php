<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\PaymentsController;

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


Route::get('/', [EventsController::class, 'index'])->name('home');
Route::get('/events/{event}', [EventsController::class, 'show'])->name('events.show');
Route::post('/payments/{event}', [PaymentsController::class, 'store'])->name('payments.store');
