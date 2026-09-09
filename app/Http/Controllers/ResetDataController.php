<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\GSTInvoice;
use App\Models\GSTInvoiceItem;
use App\Models\PerformaInvoice;
use App\Models\PerformaInvoiceItem;
use App\Models\Payment;
use App\Models\PaymentHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ResetDataController extends Controller
{
    public function getOptions()
    {
        $user = Auth::user();
        $companies = $user->companies()->select('id', 'company_name')->get();

        $currentMonth = now()->month;
        $currentYear  = now()->year;
        $defaultFY = $currentMonth < 4 ? $currentYear - 1 : $currentYear;

        $sessions = [];
        for ($i = 0; $i < 6; $i++) {
            $yr = $defaultFY - $i;
            $sessions[] = $yr . '-' . substr($yr + 1, -2);
        }

        return response()->json([
            'companies' => $companies,
            'sessions' => $sessions
        ]);
    }

    public function resetInvoiceData(Request $request)
    {
        $request->validate([
            'invoice_type' => 'required|in:gst,export,proforma',
            'company_id' => 'required|exists:companies,id',
            'session' => 'required|string',
            'confirmation' => 'required|string',
        ]);

        if (strtoupper(trim($request->confirmation)) !== 'RESET') {
            return back()->withErrors(['confirmation' => 'Please type RESET to confirm data reset.']);
        }

        $user = Auth::user();
        $company = Company::where('seller_id', $user->id)
            ->where('id', $request->company_id)
            ->first();

        if (!$company) {
            return back()->withErrors(['company_id' => 'Unauthorized or company not found.']);
        }

        if (preg_match('/^(\d{4})-\d{2}$/', $request->session, $matches)) {
            $financialYear = (int)$matches[1];
        } else {
            return back()->withErrors(['session' => 'Invalid session format. Use YYYY-YY (e.g. 2026-27).']);
        }

        $startDate = Carbon::createFromDate($financialYear, 4, 1)->startOfDay();
        $endDate   = Carbon::createFromDate($financialYear + 1, 3, 31)->endOfDay();

        DB::beginTransaction();
        try {
            $count = 0;
            if ($request->invoice_type === 'gst') {
                $invoices = GSTInvoice::where('company_id', $company->id)
                    ->whereBetween('invoice_date', [$startDate, $endDate])
                    ->get();
                $invoiceIds = $invoices->pluck('id')->toArray();
                $count = count($invoiceIds);

                if ($count > 0) {
                    $payments = Payment::where('paymentable_type', GSTInvoice::class)
                        ->whereIn('paymentable_id', $invoiceIds)
                        ->get();
                    $paymentIds = $payments->pluck('id')->toArray();

                    if (!empty($paymentIds)) {
                        PaymentHistory::whereIn('payment_id', $paymentIds)->delete();
                        Payment::whereIn('id', $paymentIds)->delete();
                    }

                    GSTInvoiceItem::whereIn('invoice_id', $invoiceIds)->delete();
                    GSTInvoice::whereIn('id', $invoiceIds)->delete();
                }

            } elseif ($request->invoice_type === 'export') {
                $invoices = Invoice::where('company_id', $company->id)
                    ->whereBetween('invoice_date', [$startDate, $endDate])
                    ->get();
                $invoiceIds = $invoices->pluck('id')->toArray();
                $count = count($invoiceIds);

                if ($count > 0) {
                    $payments = Payment::where('paymentable_type', Invoice::class)
                        ->whereIn('paymentable_id', $invoiceIds)
                        ->get();
                    $paymentIds = $payments->pluck('id')->toArray();

                    if (!empty($paymentIds)) {
                        PaymentHistory::whereIn('payment_id', $paymentIds)->delete();
                        Payment::whereIn('id', $paymentIds)->delete();
                    }

                    InvoiceItem::whereIn('invoice_id', $invoiceIds)->delete();
                    Invoice::whereIn('id', $invoiceIds)->delete();
                }

            } elseif ($request->invoice_type === 'proforma') {
                $invoices = PerformaInvoice::where('company_id', $company->id)
                    ->whereBetween('invoice_date', [$startDate, $endDate])
                    ->get();
                $invoiceIds = $invoices->pluck('id')->toArray();
                $count = count($invoiceIds);

                if ($count > 0) {
                    $payments = Payment::where('paymentable_type', PerformaInvoice::class)
                        ->whereIn('paymentable_id', $invoiceIds)
                        ->get();
                    $paymentIds = $payments->pluck('id')->toArray();

                    if (!empty($paymentIds)) {
                        PaymentHistory::whereIn('payment_id', $paymentIds)->delete();
                        Payment::whereIn('id', $paymentIds)->delete();
                    }

                    PerformaInvoiceItem::whereIn('invoice_id', $invoiceIds)->delete();
                    PerformaInvoice::whereIn('id', $invoiceIds)->delete();
                }
            }

            DB::commit();

            $typeName = [
                'gst' => 'GST Invoice',
                'export' => 'Export Invoice',
                'proforma' => 'Proforma Invoice'
            ][$request->invoice_type];

            return back()->with('success', "Data Reset Complete! Deleted {$count} {$typeName}(s) for {$company->company_name} (Session {$request->session}). Next invoice for this session will start from 00001.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reset Data Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to reset invoice data: ' . $e->getMessage()]);
        }
    }
}
