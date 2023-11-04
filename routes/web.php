<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerBillController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionPackController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('subscription', [SubscriptionPackController::class, 'index'])->name('subscription.index');
    Route::get('buy/subscription', [SubscriptionPackController::class, 'subscription'])->name('subscription.subscription');
    Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/company', [CompanyController::class, 'index'])->name('company.index');
    Route::get('/view-company', [CompanyController::class, 'view_company'])->name('company.view');
    Route::post('/store-company', [CompanyController::class, 'store_company'])->name('company.store');
    Route::post('/delete-company/{id}', [CompanyController::class, 'delete_company'])->name('company.delete');
    Route::get('/edit-company', [CompanyController::class, 'edit_company'])->name('company.edit');
    Route::post('/update-company/{id}', [CompanyController::class, 'update_company'])->name('company.update');
    Route::post('/lut-company/{id}', [CompanyController::class, 'lut_company'])->name('company.lut');
    Route::get('/company-customer', [CustomerController::class, 'index'])->name('company.customer');
    Route::get('/company-customer-view', [CustomerController::class, 'view_customer'])->name('company.customer.view');
    Route::post('/store-customer', [CustomerController::class, 'store_customer'])->name('company.customer.store');
    Route::post('/delete-customer/{id}', [CustomerController::class, 'delete_customer'])->name('company.customer.delete');
    Route::get('/edit-customer', [CustomerController::class, 'edit_customer'])->name('company.customer.edit');
    Route::post('/update-customer/{id}', [CustomerController::class, 'update_customer'])->name('company.customer.update');
    Route::get('search-customer/{searchdata}',[CustomerController::class,'search_seller_customer'])->name('search.seller.customer');
    Route::get('customer-bill',[CustomerBillController::class,'index'])->name('customer.bill');
    Route::get('customer-bill-edit',[CustomerBillController::class,'edit'])->name('customer.bill.edit');
    Route::post('customer-bill-update',[CustomerBillController::class,'update'])->name('customer.bill.update');
    Route::get('customer-detail-bill',[CustomerBillController::class,'index_details'])->name('customer.detail.bill');
    Route::post('customer-store-bill',[CustomerBillController::class,'store'])->name('customer.store.bill');
    Route::get('invoice-list',[CustomerBillController::class,'invoice_list'])->name('invoice.list');
    Route::get('view-invoice',[CustomerBillController::class,'template'])->name('view.invoice');
});

require __DIR__.'/auth.php';
