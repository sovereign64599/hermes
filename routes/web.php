<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//hermes-store-website

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

Auth::routes();
Route::match(['get'], 'login', function(){ return redirect('/'); })->name('login');

Route::group(['middleware' => 'auth'], function(){
    
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales', [App\Http\Controllers\Admin\SalesController::class, 'index'])->name('sales');

    Route::group(['middleware' => 'isAdmin'], function(){
        // user management get request
        Route::get('/show-users', [App\Http\Controllers\Admin\DashboardController::class, 'showUsers'])->name('user');
        Route::get('/add-users', [App\Http\Controllers\Admin\DashboardController::class, 'addUsers'])->name('add.user');
        Route::get('/edit-user/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'editUsers'])->name('edit.user');
        Route::get('/dleete-user/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'deleteUser'])->name('delete.user');

        // user management post request
        Route::post('/add-users', [App\Http\Controllers\Admin\DashboardController::class, 'storeUser'])->name('store.user');
        Route::patch('/update-user/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'updateUser'])->name('update.user');

        // items get
        Route::get('/edit-items/{id}', [App\Http\Controllers\Admin\ItemsController::class, 'editItems'])->name('edit.item');

        // items post
        Route::patch('/update-items/{id}', [App\Http\Controllers\Admin\ItemsController::class, 'update'])->name('update.item');
    });

    // items post action
    Route::post('/import-items', [App\Http\Controllers\Admin\ItemsController::class, 'importItems'])->name('import.items');
    Route::post('/store-items', [App\Http\Controllers\Admin\ItemsController::class, 'store'])->name('store.items');

    // items get action 
    Route::get('/export-items', [App\Http\Controllers\Admin\ItemsController::class, 'exportItems'])->name('export.items');
    Route::get('/get-items', [App\Http\Controllers\Admin\ItemsController::class, 'getItems']);
    Route::get('/view-items/{id}', [App\Http\Controllers\Admin\ItemsController::class, 'viewItems']);
    
    Route::get('/delete-items/{id}', [App\Http\Controllers\Admin\ItemsController::class, 'destroy']);
    Route::get('/filter-items/{input}', [App\Http\Controllers\Admin\ItemsController::class, 'filter']);
    Route::get('/collect-sub-categories/{id}', [App\Http\Controllers\Admin\ItemsController::class, 'collectSubCategory']);

    Route::get('/items', [App\Http\Controllers\Admin\ItemsController::class, 'index'])->name('items');
    Route::get('/transfer-in', [App\Http\Controllers\Admin\TransferInController::class, 'index'])->name('transfer.in');
    Route::get('/deduct-items', [App\Http\Controllers\Admin\DeductController::class, 'index'])->name('deduct.items');

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

    // transfer out
    Route::post('/add-list', [App\Http\Controllers\Admin\TransferInController::class, 'addList']);

    // transfer in get method
    Route::get('/collect-item-names/{id}', [App\Http\Controllers\Admin\TransferInController::class, 'collectItemName']);
    Route::get('/collect-data/{id}', [App\Http\Controllers\Admin\TransferInController::class, 'collectData']);
    

});
