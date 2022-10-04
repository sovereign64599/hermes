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
    Route::get('/collect-sub-categories/{id}', [App\Http\Controllers\Admin\TransferInController::class, 'collectSubCategory']);

    // transfer out get action
    Route::get('/show-items', [App\Http\Controllers\Admin\TransferOutController::class, 'showItems']);

    // inventory show pages
    Route::get('/transfer-in', [App\Http\Controllers\Admin\TransferInController::class, 'index'])->name('transfer.in');
    Route::get('/transfer-out', [App\Http\Controllers\Admin\TransferOutController::class, 'index'])->name('transfer.out');

    // category post action
    Route::post('/create-category', [App\Http\Controllers\Admin\CategoriesController::class, 'store'])->name('store.category');
    Route::post('/update-category', [App\Http\Controllers\Admin\CategoriesController::class, 'updateCategory'])->name('update.category');

    // category get action
    Route::get('/get-category', [App\Http\Controllers\Admin\CategoriesController::class, 'getCategory']);
    Route::get('/edit-category/{id}', [App\Http\Controllers\Admin\CategoriesController::class, 'editCategory']);
    Route::get('/filter-category/{input}', [App\Http\Controllers\Admin\CategoriesController::class, 'filter']);
    Route::get('/delete-category/{input}', [App\Http\Controllers\Admin\CategoriesController::class, 'deleteCategory']);

    // sub category post action
    Route::post('/create-sub-category', [App\Http\Controllers\Admin\SubCategoryController::class, 'store'])->name('store.sub.category');
    Route::post('/update-sub-category', [App\Http\Controllers\Admin\SubCategoryController::class, 'updateSubCategory'])->name('update.sub.category');

    // sub category get action
    Route::get('/get-sub-category', [App\Http\Controllers\Admin\SubCategoryController::class, 'getSubCategory']);
    Route::get('/edit-sub-category/{id}', [App\Http\Controllers\Admin\SubCategoryController::class, 'editSubCategory']);
    Route::get('/filter-sub-category/{input}', [App\Http\Controllers\Admin\SubCategoryController::class, 'filter']);
    Route::get('/delete-sub-category/{input}', [App\Http\Controllers\Admin\SubCategoryController::class, 'deleteSubCategory']);

    // category show pages
    Route::get('/categories', [App\Http\Controllers\Admin\CategoriesController::class, 'index'])->name('categories');
    Route::get('/sub-categories', [App\Http\Controllers\Admin\SubCategoryController::class, 'index'])->name('sub.categories');

});
