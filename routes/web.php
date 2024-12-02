<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesController;
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

require __DIR__ . '/auth.php';

Route::get('/create-user', [UserController::class, 'regUser'])->name('user.create.view');
Route::post('/create-user', [UserController::class, 'storeUser'])->name('user.create');
Route::get('/users-list', [UserController::class, 'userList'])->name('admin.user.list');
Route::get('/user-edit/{id}', [UserController::class, 'userEdit'])->name('admin.user.edit.view');
Route::put('/user-edit/{id}', [UserController::class, 'userUpdate'])->name('admin.users.update');
Route::delete('/user-delete/{id}', [UserController::class, 'userDestroy'])->name('admin.users.destroy');

Route::middleware(['permission:client.create'])->group(function () {
    Route::get('/client-create', [ClientController::class, 'create'])->middleware('auth')->name('client.create');
    Route::post('/client-create', [ClientController::class, 'store'])->middleware('auth')->name('client.store');
});
Route::get('/client-list-data', [ClientController::class, 'list'])->middleware('permission:client.view')->name('client.list');
Route::middleware(['permission:client.edit'])->group(function () {
    Route::get('/client-edit/{id}', [ClientController::class, 'edit'])->middleware('auth')->name('client.edit.view');
    Route::put('/client-edit/{id}', [ClientController::class, 'update'])->middleware('auth')->name('client.edit');
});
Route::delete('/client-delete/{id}', [ClientController::class, 'destroy'])->middleware('permission:client.destroy')->name('client.destroy');

// Route::middleware(['permission:product.create'])->group(function () {
Route::get('/product-create', [ProductController::class, 'create'])->middleware('auth')->name('product.create');
Route::post('/product-create', [ProductController::class, 'store'])->middleware('auth')->name('product.store');
// });

// Route::middleware(['permission:product.list.view'])->group(function () {
Route::get('/product-list', [ProductController::class, 'listView'])->middleware('auth')->name('product.list.view');
Route::get('/product-list-data', [ProductController::class, 'list'])->middleware('auth')->name('product.list');
// });

// Route::middleware(['permission:product.edit'])->group(function () {
Route::get('/product-edit/{id}', [ProductController::class, 'edit'])->middleware('auth')->name('product.edit.view');
Route::put('/product-update/{id}', [ProductController::class, 'update'])->middleware('auth')->name('product.edit');
// });

Route::delete('/product-delete/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

// Route::middleware(['permission:category.create'])->group(function () {
Route::get('/category-create', [CategoryController::class, 'create'])->middleware('auth')->name('category.create');
Route::post('/category-create', [CategoryController::class, 'store'])->middleware('auth')->name('category.store');
// });
Route::get('/category-list-data', [CategoryController::class, 'list'])->middleware('auth')->middleware('auth')->name('category.list');

// Route::middleware(['permission:category.edit'])->group(function () {

Route::get('/category-edit/{id}', [CategoryController::class, 'edit'])->middleware('auth')->name('category.edit.view');
Route::put('/category-edit/{id}', [CategoryController::class, 'update'])->middleware('auth')->name('category.edit');
// });

Route::delete('/category-delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

// Route::middleware(['permission:productin.view'])->group(function () {
Route::get('/product-in', [ProductInController::class, 'view'])->middleware('auth')->name('productin.view');
Route::post('/product-in', [ProductInController::class, 'store'])->middleware('auth')->name('productin.store');
// });
Route::get('/product/{id}/categories', [ProductController::class, 'getCategoriesForProduct']);
// Route::middleware(['permission:productin.view'])->group(function () {
Route::get('/product-in-report', [ProductInController::class, 'report'])->middleware('auth')->name('productin.report.view');
Route::get('/product-in-report-data', [ProductInController::class, 'report_data'])->middleware('auth')->name('productin.report');
// });
// Route::middleware(['permission:productin.view'])->group(function () {
Route::get('/inventory-report', [InventoryController::class, 'report'])->middleware('auth')->name('productin.report.view');
Route::get('/inventory-report-data', [InventoryController::class, 'report_data'])->middleware('auth')->name('productin.report');
// });

// Route::middleware(['permission:productin.view'])->group(function () {
Route::get('/sales', [SalesController::class, 'view'])->middleware('auth')->name('sales.view');
Route::get('/sales-inventory-data', [SalesController::class, 'data'])->middleware('auth');
Route::post('/sales-store', [SalesController::class, 'store'])->middleware('auth');
// });

// Route::middleware(['permission:productin.view'])->group(function () {
    Route::get('/sales-report', [SalesController::class, 'report_view'])->middleware('auth')->name('sales.view');
    Route::get('/sales-report-data', [SalesController::class, 'report_data'])->middleware('auth');
    Route::post('/sales-report-invoice', [SalesController::class, 'getInvoiceData']);

    // });
// Route::middleware(['role:Admin'])->group(function () {
Route::get('/permission-list', [PermissionController::class, 'index'])->name('permission.list');
Route::get('/permission-list-data', [PermissionController::class, 'listData'])->name('permission.list.data');

Route::get('/permission-create', [PermissionController::class, 'create'])->name('permission.create.view');
Route::post('/permission-create', [PermissionController::class, 'store'])->name('permission.create');
Route::get('/permission-edit/{id}', [PermissionController::class, 'edit'])->name('permission.edit');
Route::get('/permission-edit/{id}', [PermissionController::class, 'edit'])->name('permission.edit');
Route::put('/permission-edit/{id}', [PermissionController::class, 'update'])->name('permission.update');
Route::delete('/permission-delete/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');

Route::get('/role-list', [RoleController::class, 'index'])->name('admin.roles');
Route::get('/role-create', [RoleController::class, 'create'])->name('admin.roles.create.view');
Route::post('/roles-create', [RoleController::class, 'store'])->name('admin.roles.create');

Route::get('/role-edit/{id}', [RoleController::class, 'edit'])->name('admin.roles.edit.view');
Route::put('/role-edit/{id}', [RoleController::class, 'update'])->name('admin.roles.update');
Route::delete('/role-delete/{id}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');

// });
