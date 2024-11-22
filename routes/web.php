<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/tables', [UserController::class, 'tables']);

require __DIR__.'/auth.php';



Route::get('/create-user', [UserController::class, 'regUser'])->name('user.create.view');
Route::post('/create-user', [UserController::class, 'storeUser'])->name('user.create');
Route::get('/users-list', [UserController::class, 'userList'])->name('admin.user.list');
Route::get('/user-edit/{id}', [UserController::class, 'userEdit'])->name('admin.user.edit.view');
Route::put('/user-edit/{id}', [UserController::class, 'userUpdate'])->name('admin.users.update');
Route::delete('/user-delete/{id}', [UserController::class, 'userDestroy'])->name('admin.users.destroy');


// Route::middleware(['permission:client.create'])->group(function () {
    Route::get('/client-create', [ClientController::class, 'create'])->middleware('auth')->name('client.create');
    Route::post('/client-create', [ClientController::class, 'store'])->middleware('auth')->name('client.store');
    // });
    Route::get('/client-list-data', [ClientController::class, 'list'])->middleware('auth')->name('client.list');
    // Route::middleware(['permission:client.edit'])->group(function () {
    Route::get('/client-edit/{id}', [ClientController::class,'edit'])->middleware('auth')->name('client.edit.view');
    Route::put('/client-edit/{id}', [ClientController::class,'update'])->middleware('auth')->name('client.edit');
    // });
    Route::delete('/client-delete/{id}', [ClientController::class,'destroy'])->name('client.destroy');
    

    // Route::middleware(['permission:product.create'])->group(function () {
        Route::get('/product-create', [ProductController::class, 'create'])->middleware('auth')->name('product.create');
        Route::post('/product-create', [ProductController::class, 'store'])->middleware('auth')->name('product.store');
        // });
        
        // Route::middleware(['permission:product.list.view'])->group(function () {
        Route::get('/product-list', [ProductController::class, 'listView'])->middleware('auth')->name('product.list.view');
        Route::get('/product-list-data', [ProductController::class, 'list'])->middleware('auth')->name('product.list');
        // });
        
        // Route::middleware(['permission:product.edit'])->group(function () {
        Route::get('/product-edit/{id}', [ProductController::class,'edit'])->middleware('auth')->name('product.edit.view');
        Route::post('/product-edit/{id}', [ProductController::class,'update'])->middleware('auth')->name('product.edit');
        // });
        
        Route::delete('/product-delete/{id}', [ProductController::class,'destroy'])->name('product.destroy');