<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerBillController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GSTInvoiceController;
use App\Http\Controllers\HsnSacMasterController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MastergstController;
use App\Http\Controllers\PaymentRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionPackController;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SellerCustomers;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
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
})->name('home');

Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
})->name('privacy.policy');

Route::get('/terms-of-service', function () {
    return Inertia::render('TermsOfService');
})->name('terms.service');

Route::get('/cookie-policy', function () {
    return Inertia::render('CookiePolicy');
})->name('cookie.policy');

Route::get('/dashboard', function () {
    $user = Auth::user();

    $companyIds = $user->companies()->pluck('id');

    $stats = [
        'totalRevenue' => Payment::whereHasMorph(
                                'paymentable',
                                [\App\Models\Invoice::class],
                                function ($q) use ($companyIds) {
                                    $q->whereIn('company_id', $companyIds);
                                })
                                ->sum('total_amount'),

        'totalPaid' => Payment::whereHasMorph(
                                'paymentable',
                                [\App\Models\Invoice::class],
                                function ($q) use ($companyIds) {
                                    $q->whereIn('company_id', $companyIds);
                                })
                                ->sum('paid_amount'),

        'totalDue' => Payment::whereHasMorph(
                                'paymentable',
                                [\App\Models\Invoice::class],
                                function ($q) use ($companyIds) {
                                    $q->whereIn('company_id', $companyIds);
                                })
                                ->selectRaw('SUM(total_amount - paid_amount) as due')
                                ->value('due'),

        'totalCustomers' => Invoice::whereIn('company_id', $companyIds)
                                    ->distinct('customer_company_id')
                                    ->count('customer_company_id'),
    ];

    $recentActivity = Invoice::with(['company', 'payment', 'paymentStatus' => function ($q) {
                        $q->latest('created_at')->take(1); // get latest payment record
                        }])
                        ->whereIn('company_id', $companyIds)
                        ->latest('invoice_date')
                        ->take(5)
                        ->get()
                        ->map(function ($invoice) {
                            $latestPayment = $invoice->payment->first(); // this is now a Collection
                            $invoice->payment_status = $latestPayment ? $latestPayment->status : 'due';
                            return $invoice;
                        });

    // Get user's subscription information
    $subscription = $user->subscriptions()
                         ->with(['sellerSubscription']) // assuming you have a plan relationship
                        //  ->where('status', '10') // or whatever status field you use
                         ->latest()
                         ->first();

    return Inertia::render('Dashboard', [
        'stats' => $stats,
        'recentActivity' => $recentActivity,
        'subscription' => $subscription
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('master_gst_auth',[MastergstController::class,'master_gst_auth'])->name('master.gst.auth');
Route::middleware('auth')->group(function () {
    
    Route::post('create-eway-bill',[MastergstController::class,'create_eway_bill'])->name('create.eway.bill');

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
    Route::post('/lut-company/{id}', [CompanyController::class, 'lut_commpany'])->name('company.lut');
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
    Route::post('bill-mail',[MailController::class,'billmail'])->name('bill.sendmail');

    Route::resource('payment-request', PaymentRequestController::class);

    // performa invoices
    Route::group(['prefix'=>'performa','as'=>'performa.'],function(){
        Route::get('customer-bill',[CustomerBillController::class,'performa_index'])->name('customer.bill');
        Route::get('customer-bill-edit',[CustomerBillController::class,'performa_edit'])->name('customer.bill.edit');
        Route::post('customer-bill-update',[CustomerBillController::class,'performa_update'])->name('customer.bill.update');
        Route::get('customer-detail-bill',[CustomerBillController::class,'performa_index_details'])->name('customer.detail.bill');
        Route::post('customer-store-bill',[CustomerBillController::class,'performa_store'])->name('customer.store.bill');
        Route::get('invoice-list',[CustomerBillController::class,'performa_invoice_list'])->name('invoice.list');
        Route::post('bill-mail',[MailController::class,'performa_billmail'])->name('bill.sendmail');
    });

    // GST invoices
    Route::group(['prefix'=>'gst','as'=>'gst.'],function(){
        Route::get('customer-bill',[GSTInvoiceController::class,'gst_index'])->name('customer.bill');
        Route::get('customer-bill-edit',[GSTInvoiceController::class,'gst_edit'])->name('customer.bill.edit');
        Route::post('customer-bill-update/{invoice_id}',[GSTInvoiceController::class,'gst_update'])->name('customer.bill.update');
        Route::get('customer-detail-bill',[GSTInvoiceController::class,'gst_index_details'])->name('customer.detail.bill');
        Route::post('customer-store-bill',[GSTInvoiceController::class,'gst_store'])->name('customer.store.bill');
        Route::get('invoice-list',[GSTInvoiceController::class,'gst_invoice_list'])->name('invoice.list');
        Route::post('bill-mail',[MailController::class,'gst_billmail'])->name('bill.sendmail');
    });

    Route::get('old-payments-feed',[CustomerBillController::class,'Fetch_bill_create_payment_for_old_data']);
    //Customer My Bill Route
    Route::get('my-bill', [CustomerBillController::class, 'myBill'])->name('customer.mybill');

    // routes/web.php
    Route::get('/hsn/search', [HsnSacMasterController::class, 'search'])->name('hsn.search');

});
Route::get('view-invoice',[CustomerBillController::class,'template'])->name('view.invoice');
Route::get('performa/view-invoice',[CustomerBillController::class,'performa_template'])->name('performa.view.invoice');
Route::get('gst/view-invoice',[GSTInvoiceController::class,'gst_template'])->name('gst.view.invoice');

require __DIR__.'/auth.php';
