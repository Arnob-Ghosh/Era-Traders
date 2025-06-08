<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CustomerDepositController;
use App\Http\Controllers\DueReportController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\ProductReturnController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\UnitController;
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
Route::get('/clear', function () {
    // Run Artisan commands
    Artisan::call('optimize:clear');
    return redirect()->back();
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->middleware('auth')->name('logout');

Route::get('/tables', [UserController::class, 'tables']);

require __DIR__ . '/auth.php';


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

Route::get('/deposit-create', [CustomerDepositController::class, 'create'])->middleware('auth')->name('deposit.create');
Route::post('/deposit-create', [CustomerDepositController::class, 'store'])->middleware('auth')->name('deposit.store');


Route::get('/deposit-edit/{id}', [CustomerDepositController::class, 'edit'])->middleware('auth')->name('deposit.edit.view');
Route::put('/deposit-edit/{id}', [CustomerDepositController::class, 'update'])->middleware('auth')->name('deposit.edit');
// });

Route::get('/deposit-list-data', [CustomerDepositController::class, 'list'])->middleware('auth')->name('deposit.list');

Route::delete('/deposit-delete/{id}', [CustomerDepositController::class, 'destroy'])->name('deposit.destroy');
// Route::middleware(['permission:productin.view'])->group(function () {
Route::get('/product-in', [ProductInController::class, 'view'])->middleware('auth')->name('productin.view');
Route::post('/product-in', [ProductInController::class, 'store'])->middleware('auth')->name('productin.store');
// });
// Route::middleware(['permission:productin.view'])->group(function () {
    Route::get('/product-in-return', [ProductReturnController::class, 'view'])->middleware('auth')->name('productin-return.view');
    Route::post('/product-in-return', [ProductReturnController::class, 'store'])->middleware('auth')->name('productin-return.store');
    Route::get('/product/{productId}/category/{categoryId}/prices', [ProductReturnController::class, 'getPrice']);

    // });
    // Route::middleware(['permission:productin.view'])->group(function () {
        Route::get('/product-in-return-report', [ProductReturnController::class, 'report_view'])->middleware('auth')->name('productin-return.report.view');
        Route::get('/product-in-return-report-data', [ProductReturnController::class, 'report_data'])->middleware('auth')->name('productin-return.report');
    
        // });
Route::get('/product/{id}/categories', [ProductController::class, 'getCategoriesForProduct']);
// Route::middleware(['permission:productin.view'])->group(function () {
Route::get('/product-in-report', [ProductInController::class, 'report'])->middleware('auth')->name('productin.report.view');
Route::get('/product-in-report-data', [ProductInController::class, 'report_data'])->middleware('auth')->name('productin.report');
// });
// Route::middleware(['permission:productin.view'])->group(function () {
Route::get('/inventory-report', [InventoryController::class, 'report'])->middleware('auth')->name('productin.report.view');
Route::get('/inventory-report-data', [InventoryController::class, 'report_data'])->middleware('auth')->name('productin.report');
Route::get('/inventory-report-data-quantity-wise', [InventoryController::class, 'quantiy_wise_report_data'])->middleware('auth')->name('quantitywise-inventory.report');
// });

Route::get('/due-report', [DueReportController::class, 'index'])->middleware('auth')->name('due.report.view');
Route::get('/due-report-data', [DueReportController::class, 'due_data'])->middleware('auth')->name('due.report.data');


// Route::middleware(['permission:productin.view'])->group(function () {
Route::get('/sales', [SalesController::class, 'view'])->middleware('auth')->name('sales.view');
Route::get('/sales-inventory-data', [SalesController::class, 'data'])->middleware('auth');
Route::post('/sales-store', [SalesController::class, 'store'])->middleware('auth');
// });
// Route::middleware(['permission:productin.view'])->group(function () {
    Route::get('/sales-return', [SalesReturnController::class, 'view'])->middleware('auth')->name('sales.view');
    Route::post('/sales-retutrn-store', [SalesReturnController::class, 'store'])->middleware('auth');
    Route::get('/sales-info-data', [SalesReturnController::class, 'sales_info_data'])->middleware('auth');
    Route::post('/sales-list', [SalesReturnController::class, 'store'])->middleware('auth');
    Route::post('/sales-list-data', [SalesReturnController::class, 'store'])->middleware('auth');
    // });
// Route::middleware(['permission:productin.view'])->group(function () {
    Route::get('/sales-report', [SalesController::class, 'report_view'])->middleware('auth')->name('sales.view');
    Route::get('/sales-report-data', [SalesController::class, 'report_data'])->middleware('auth');
    Route::post('/sales-report-invoice', [SalesController::class, 'getInvoiceData']);

    // });
// Route::middleware(['role:Admin'])->group(function () {


Route::get('/create-user', [UserController::class, 'regUser'])->name('user.create.view');
Route::post('/create-user', [UserController::class, 'storeUser'])->name('user.create');
Route::get('/users-list', [UserController::class, 'userList'])->name('admin.user.list');
Route::get('/user-edit/{id}', [UserController::class, 'userEdit'])->name('admin.user.edit.view');
Route::put('/user-edit/{id}', [UserController::class, 'userUpdate'])->name('admin.users.update');
Route::delete('/user-delete/{id}', [UserController::class, 'userDestroy'])->name('admin.users.destroy');

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


// Route::middleware(['permission:unit.create'])->group(function () {
    Route::get('/unit-create', [UnitController::class, 'create'])->middleware('auth')->name('unit.create');
    Route::post('/unit-create', [UnitController::class, 'store'])->middleware('auth')->name('unit.store');
    // });
    
    Route::get('/unit-list-data', [UnitController::class, 'list'])->name('unit.list');
    // Route::middleware(['permission:vat.edit'])->group(function () {
    
    Route::get('/unit-edit/{id}', [UnitController::class,'edit'])->middleware('auth')->name('unit.edit.view');
    Route::put('/unit-edit/{id}', [UnitController::class,'update'])->middleware('auth')->name('unit.edit');
    // });
    
    Route::delete('/unit-delete/{id}', [UnitController::class,'destroy'])->name('unit.destroy');