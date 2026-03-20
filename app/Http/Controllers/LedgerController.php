<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\SellerCustomers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LedgerController extends Controller
{
    public function index()
    {
        $ledgers = Ledger::with('sellerCustomer')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return Inertia::render('Ledger/Index', compact('ledgers'));
    }

    public function create()
    {
        $customers = SellerCustomers::where('seller_id', Auth::id())->get();
        return Inertia::render('Ledger/Create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'seller_customer_id' => 'required|exists:seller_customers,id',
            'type' => 'required|in:debit,credit',
            'payment_type' => 'required|in:cash,transfer',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Ledger::create([
            'user_id' => Auth::id(),
            'seller_customer_id' => $request->seller_customer_id,
            'type' => $request->type,
            'payment_type' => $request->payment_type,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->route('ledgers.index')->with('success', 'Ledger entry created successfully');
    }

    public function edit(Ledger $ledger)
    {
        if ($ledger->user_id !== Auth::id()) {
            abort(403);
        }

        $customers = SellerCustomers::where('seller_id', Auth::id())->get();
        return Inertia::render('Ledger/Edit', compact('ledger', 'customers'));
    }

    public function update(Request $request, Ledger $ledger)
    {
        if ($ledger->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'seller_customer_id' => 'required|exists:seller_customers,id',
            'type' => 'required|in:debit,credit',
            'payment_type' => 'required|in:cash,transfer',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $ledger->update($request->only([
            'seller_customer_id',
            'type',
            'payment_type',
            'amount',
            'date',
            'description',
        ]));

        return redirect()->route('ledgers.index')->with('success', 'Ledger entry updated successfully');
    }

    public function destroy(Ledger $ledger)
    {
        if ($ledger->user_id !== Auth::id()) {
            abort(403);
        }

        $ledger->delete();

        return redirect()->route('ledgers.index')->with('success', 'Ledger entry deleted successfully');
    }
}
