<?php

namespace App\Http\Controllers;

use App\Models\SellerCustomers;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {
        $inv_tax_type = Status::where('for_module', 'inv_tex_type')->get();
        return Inertia::render('Customers/index', compact('inv_tax_type'));
    }

    public function search_seller_customer($data)
    {
        $searchdata = User::where('gst', $data)->first();
        $inv_tax_type = Status::where('for_module', 'inv_tex_type')->get();
        return Inertia::render('Customers/index', compact('inv_tax_type', 'searchdata'));
    }

    public function store_customer(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'gst' => 'required',
            'address' => 'required',
            'pin' => 'required',
            'tax_type' => 'required',
        ]);
        if ($valid) {
            $seller_id = Auth::id();
            $customer_id = User::firstOrCreate(['gst' => $request->gst], [
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'gst' => $request->gst,
                'address' => $request->address,
                'pin' => $request->pin,
                'inv_tax_type' => $request->tax_type,
                'password' => Hash::make('password'),
            ])->id;

            // Find or create a SellerCustomers record based on seller_id and customer_id
            $seller_customer = SellerCustomers::firstOrCreate([
                'seller_id' => $seller_id,
                'customer_id' => $customer_id,
            ], [
                'customer_data' => json_encode($request->all()), // Convert the array to JSON
            ]);


            return redirect()->route('company.customer')->with('success', 'Customer Added Successfully');
        }

        return Redirect::route('company.customer')->withErrors($valid);
    }

    public function view_customer(){
        $customers = SellerCustomers::where('seller_id', Auth::id())->paginate(10);
        return Inertia::render('Customers/view', compact('customers'));
    }

    public function edit_customer(Request $request){
        $edit_customers = SellerCustomers::find($request->customer_id);
        $inv_tax_type = Status::where('for_module', 'inv_tex_type')->get();
        return inertia('Customers/Edit',compact('edit_customers','inv_tax_type'));
    }

    public function update_customer(Request $request,$id){
        $valid = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'gst' => 'required',
            'address' => 'required',
            'pin' => 'required',
            'tax_type' => 'required',
        ]);
        if($valid){
            SellerCustomers::find($id)->update([
                'customer_data'=>json_encode($request->all())
            ]);
            return redirect()->route('company.customer.view')->with('success', 'Customer added successfully');

        }
        return Redirect::route('company.customer')->withErrors($valid);
    }

    public function delete_customer($id){
        $customer = SellerCustomers::find($id);
        $customer->delete();
        return redirect()->route('company.customer.view')->with('success', 'Customer deleted successfully');
    }

}
