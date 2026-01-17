<?php

use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\CustomerBillController;
use App\Http\Controllers\PaymentTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('fetch-invoice/{id}/{type?}', [InvoiceController::class, 'fetchInvoice']);
Route::post('update-invoice-package', [InvoiceController::class, 'updateInvoicePackage']);

//Fetch payment types
Route::get('fetch-payment-types', [PaymentTypeController::class, 'fetchPaymentType']);

//Fetch payment types
Route::post('pay-bill', [CustomerBillController::class, 'payBill']);
