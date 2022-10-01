<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

Auth::routes();
Route::match(['get'], 'login', function(){ return redirect('/'); })->name('login');

Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
Route::get('/transfer-in', [App\Http\Controllers\Admin\TransferInController::class, 'index'])->name('transfer.in');
Route::get('/transfer-out', [App\Http\Controllers\Admin\TransferOutController::class, 'index'])->name('transfer.out');
