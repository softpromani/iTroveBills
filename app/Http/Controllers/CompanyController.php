<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyLUT;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function index()
    {
        return Inertia::render('company/index');
    }
    public function view_company()
    {
        $company_details = Company::where('seller_id',Auth::id())->paginate(2);
        $company_details->load('CompanyStatus');
        return Inertia::render('company/view', compact('company_details'));
    }
    public function store_company(Request $request)
    {

        $valid = $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'email|required|unique:companies,email',
            'mobile' => 'required', // Example mobile validation rules
            'address' => 'required|string',
            'city' => 'required|string',
            'pin' => 'required',
            'gstin' => 'required|unique:companies,gstin',
            'iec' => 'required',
            'invoice_series' => 'required',
            'bank_name' => 'required',
            'bank_branch' => 'required',
            'bank_ifsc' => 'required',
            'bank_account_no' => 'required',
            'adcode' => 'required',
            'logo' => 'required|image|mimes:jpg,png,jpeg',
            'sign' => 'required|image|mimes:jpg,png,jpeg',
        ]);
        if ($request->hasFile('logo')) {
            $logo = $request->logo;
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('company_file/logo/'), $logoName);
            $upload_logo = 'company_file/logo/' . $logoName;
        }
        if ($request->hasFile('sign')) {
            $sign = $request->sign;
            $signName = time() . '.' . $sign->getClientOriginalExtension();
            $sign->move(public_path('company_file/sign/'), $signName);
            $upload_sign = 'company_file/sign/' . $signName;
        }
        if ($valid) {
            $company = Company::create([
                'company_name' => $request->company_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'city' => $request->city,
                'pin' => $request->pin,
                'gstin' => $request->gstin,
                'iec' => $request->iec,
                'invoice_series' => $request->invoice_series,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'bank_ifsc' => $request->bank_ifsc,
                'bank_account_no' => $request->bank_account_no,
                'ad_code' => $request->adcode,
                'seller_id' => Auth::id(),
                'status' => Status::moduleStatusId('Company','active'),
                'logo' => $upload_logo,
                'sign' => $upload_sign,
            ]);
            if($company){
                return redirect()->route('company.index')->with('success', 'Company created successfully!');
            }
        }
        return Redirect::route('company.index')->withErrors($valid);
    }


    public function delete_company($id){
        $company = Company::find($id);
        $company->delete();
        return redirect()->route('company.view')->with('success', 'Company deleted successfully!');
    }

    public function lut_company(Request $request,$id){
        $valid = $request->validate([
            'lut_no' => 'required',
            'expiry_date' => 'required',
        ]);
        if ($valid) {
          $lut = CompanyLUT::create([
                'company_id'=>$id,
                'status'=>Status::moduleStatusId('Company','active'),
                'expiry_date'=>$request->expiry_date,
                'lut_no'=>$request->lut_no,
            ]);

            if($lut){
                return redirect()->route('company.view')->with('success', 'Company LUT created successfully!');
            }
            return Redirect::route('company.view')->withErrors($valid);
        }
    }

    public function edit_company(Request $request){
        $edata = Company::find($request->id);
        return Inertia::render('company/index',compact('edata'));
    }
    public function update_company(Request $request,$id){
        $valid = $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'email',
            'mobile' => 'required', // Example mobile validation rules
            'address' => 'required|string',
            'city' => 'required|string',
            'pin' => 'required',
            'gstin' => 'required',
            'iec' => 'required',
            'invoice_series' => 'required',
            'bank_name' => 'required',
            'bank_branch' => 'required',
            'bank_ifsc' => 'required',
            'bank_account_no' => 'required',
            'adcode' => 'required',
        ]);
        if ($request->hasFile('logo')) {
            if (Company::find($id)->logo) {
                $oldImagePath = public_path(Company::find($id)->logo);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            $logo = $request->logo;
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('company_file/logo/'), $logoName);
            $upload_logo = 'company_file/logo/' . $logoName;
            Company::find($id)->update([
                'logo' =>  $upload_logo
            ]);
        }
        if ($request->hasFile('sign')) {
            if (Company::find($id)->sign) {
                $oldImagePath = public_path(Company::find($id)->sign);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            $sign = $request->sign;
            $signName = time() . '.' . $sign->getClientOriginalExtension();
            $sign->move(public_path('company_file/sign/'), $signName);
            $upload_sign = 'company_file/sign/' . $signName;
            Company::find($id)->update([
                'sign' =>  $upload_sign
            ]);
        }
        if ($valid) {
            $company = Company::find($id)->update([
                'company_name' => $request->company_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'city' => $request->city,
                'pin' => $request->pin,
                'gstin' => $request->gstin,
                'iec' => $request->iec,
                'invoice_series' => $request->invoice_series,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'bank_ifsc' => $request->bank_ifsc,
                'bank_account_no' => $request->bank_account_no,
                'ad_code' => $request->adcode,
                'seller_id' => Auth::id(),
            ]);
            if($company){
                return redirect()->route('company.view')->with('success', 'Company updated successfully!');
            }
        }
        return Redirect::route('company.index')->withErrors($valid);
    }
}
