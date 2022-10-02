<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

Auth::routes();
Route::match(['get'], 'login', function(){ return redirect('/'); })->name('login');

Route::group(['middleware' => 'auth'], function(){
    
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // transfer in post action
    Route::post('/store-items', [App\Http\Controllers\Admin\TransferInController::class, 'store']);
    Route::post('/update-items', [App\Http\Controllers\Admin\TransferInController::class, 'update']);
    Route::post('/update-exist-items', [App\Http\Controllers\Admin\TransferInController::class, 'updateExistItem']);

    // transfer in get action 
    Route::get('/get-items', [App\Http\Controllers\Admin\TransferInController::class, 'getItems']);
    Route::get('/check-items', [App\Http\Controllers\Admin\TransferInController::class, 'checkItems']);
    Route::get('/edit-items/{id}', [App\Http\Controllers\Admin\TransferInController::class, 'editItems']);
    Route::get('/delete-items/{id}', [App\Http\Controllers\Admin\TransferInController::class, 'destroy']);
    Route::get('/filter-items/{input}', [App\Http\Controllers\Admin\TransferInController::class, 'filter']);

    // transfer out get action
    Route::get('/show-items', [App\Http\Controllers\Admin\TransferOutController::class, 'showItems']);

    // pages to show
    Route::get('/transfer-in', [App\Http\Controllers\Admin\TransferInController::class, 'index'])->name('transfer.in');
    Route::get('/transfer-out', [App\Http\Controllers\Admin\TransferOutController::class, 'index'])->name('transfer.out');
    Route::get('/show-categories', [App\Http\Controllers\Admin\CategoriesController::class, 'index'])->name('categories');

});
