<?php

namespace App\Http\Controllers;

use App\Models\GSTInvoice;
use App\Models\Invoice;
use App\Models\Ledger;
use App\Models\SellerCustomers;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class LedgerReportController extends Controller
{
    public function index()
    {
        $customers = SellerCustomers::where('seller_id', Auth::id())->get();
        return Inertia::render('Ledger/Report', [
            'customers' => $customers,
            'filters' => [
                'seller_customer_id' => '',
                'start_date' => '',
                'end_date' => '',
            ],
            'report_data' => null
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'seller_customer_id' => 'required|exists:seller_customers,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $seller_customer = SellerCustomers::findOrFail($request->seller_customer_id);
        $customer_company_id = $seller_customer->customer_company_id;
        $customer_data = $seller_customer->customer_detail;
        
        $start_date = $request->start_date ? Carbon::parse($request->start_date) : null;
        $end_date = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        // 1. Calculate Opening Balance (Transactions before start_date)
        $opening_balance = 0;
        if ($start_date) {
            $inv_debit_before = Invoice::where('customer_company_id', $customer_company_id)
                ->where('invoice_date', '<', $start_date)
                ->sum('total_ammount');

            $gst_inv_debit_before = GSTInvoice::where('customer_company_id', $customer_company_id)
                ->where('invoice_date', '<', $start_date)
                ->sum('total_ammount');

            $ledger_debit_before = Ledger::where('seller_customer_id', $request->seller_customer_id)
                ->where('date', '<', $start_date)
                ->where('type', 'debit')
                ->sum('amount');

            $ledger_credit_before = Ledger::where('seller_customer_id', $request->seller_customer_id)
                ->where('date', '<', $start_date)
                ->where('type', 'credit')
                ->sum('amount');

            $opening_balance = ($inv_debit_before + $gst_inv_debit_before + $ledger_debit_before) - $ledger_credit_before;
        }

        // 2. Fetch Transactions within range
        $query_invoices = Invoice::where('customer_company_id', $customer_company_id)
            ->where('invoice_date', '<=', $end_date);
        if ($start_date) $query_invoices->where('invoice_date', '>=', $start_date);
        $invoices = $query_invoices->get()->map(function($item) {
            return [
                'date' => $item->invoice_date,
                'particulars' => 'SALE',
                'vch_type' => 'Sales',
                'vch_no' => $item->invoice_number,
                'debit' => (float)$item->total_ammount,
                'credit' => 0,
                'timestamp' => Carbon::parse($item->invoice_date)->timestamp
            ];
        });

        $query_gst = GSTInvoice::where('customer_company_id', $customer_company_id)
            ->where('invoice_date', '<=', $end_date);
        if ($start_date) $query_gst->where('invoice_date', '>=', $start_date);
        $gst_invoices = $query_gst->get()->map(function($item) {
            return [
                'date' => $item->invoice_date,
                'particulars' => 'SALE',
                'vch_type' => 'GST Sales',
                'vch_no' => $item->invoice_number,
                'debit' => (float)$item->total_ammount,
                'credit' => 0,
                'timestamp' => Carbon::parse($item->invoice_date)->timestamp
            ];
        });

        $query_ledger = Ledger::where('seller_customer_id', $request->seller_customer_id)
            ->where('date', '<=', $end_date);
        if ($start_date) $query_ledger->where('date', '>=', $start_date);
        $ledgers = $query_ledger->get()->map(function($item) {
            return [
                'date' => $item->date,
                'particulars' => $item->description ?: ($item->type === 'debit' ? 'Debit Adjustment' : 'Payment Received'),
                'vch_type' => $item->payment_type === 'cash' ? 'Cash' : 'Bank/Transfer',
                'vch_no' => $item->id,
                'debit' => $item->type === 'debit' ? (float)$item->amount : 0,
                'credit' => $item->type === 'credit' ? (float)$item->amount : 0,
                'timestamp' => Carbon::parse($item->date)->timestamp
            ];
        });

        // Combine and Sort
        $transactions = $invoices->concat($gst_invoices)->concat($ledgers)
            ->sortBy('date') // Primary sort: date
            ->values();

        // Calculate running balance and totals
        $running_balance = $opening_balance;
        $total_debit = 0;
        $total_credit = 0;

        foreach ($transactions as &$tx) {
            $running_balance += ($tx['debit'] - $tx['credit']);
            $tx['balance'] = $running_balance;
            $total_debit += $tx['debit'];
            $total_credit += $tx['credit'];
        }

        $closing_balance = $running_balance;

        // Company Details (For Header)
        $company = Company::where('seller_id', Auth::id())->first();

        return Inertia::render('Ledger/Report', [
            'customers' => SellerCustomers::where('seller_id', Auth::id())->get(),
            'report_data' => [
                'company' => $company,
                'customer' => $customer_data,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'opening_balance' => $opening_balance,
                'transactions' => $transactions,
                'total_debit' => $total_debit,
                'total_credit' => $total_credit,
                'closing_balance' => $closing_balance,
            ],
            'filters' => $request->only(['seller_customer_id', 'start_date', 'end_date'])
        ]);
    }
}
