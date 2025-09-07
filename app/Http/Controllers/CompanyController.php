<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyLUT;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function index()
    {
        return Inertia::render('company/index');
    }
    public function view_company()
    {
        $company_details = Company::where('seller_id',Auth::id())->paginate(10);
        $company_details->load('CompanyStatus');
        return Inertia::render('company/view', compact('company_details'));
    }
    public function store_company(Request $request)
    {
        $valid = $request->validate([
            'company_name'      => 'required|string|max:255',
            'email'             => 'required|email|unique:companies,email',
            'mobile'            => 'required',
            'address'           => 'required|string',
            'city'              => 'required|string',
            'pin'               => 'required',
            'gstin'             => 'required|unique:companies,gstin',
            'iec'               => 'required',
            'invoice_series'    => 'required',
            'bank_name'         => 'required',
            'bank_branch'       => 'required',
            'bank_ifsc'         => 'required',
            'bank_account_no'   => 'required',
            'adcode'            => 'required',
            'logo'              => 'required|image|mimes:jpg,png,jpeg',
            'sign'              => 'required|image|mimes:jpg,png,jpeg',
            'bank_qr'           => 'nullable',
            'brand_banner.*'    => 'nullable',
        ]);

        // Logo upload
        $upload_logo = null;
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('company_file/logo/'), $logoName);
            $upload_logo = 'company_file/logo/' . $logoName;
        }

        // Sign upload
        $upload_sign = null;
        if ($request->hasFile('sign')) {
            $sign = $request->file('sign');
            $signName = time() . '.' . $sign->getClientOriginalExtension();
            $sign->move(public_path('company_file/sign/'), $signName);
            $upload_sign = 'company_file/sign/' . $signName;
        }

        // Bank QR upload
        $upload_bank_qr = null;
        if ($request->hasFile('bank_qr')) {
            $bank_qr = $request->file('bank_qr');
            $bankQRName = time() . '.' . $bank_qr->getClientOriginalExtension();
            // Save to storage/app/public/company_file/bank_qr/
            $path = $bank_qr->storeAs('company_file/bank_qr', $bankQRName, 'public');
            // Store web-accessible path (after running php artisan storage:link)
            $upload_bank_qr = 'storage/' . $path;
        }

        // New banners array
        $newBanners = [];
        // Handle Brand Banners
        if ($request->brand_banner) {
            // Upload new banners
            foreach ($request->brand_banner as $bannerFile) {
                $bannerFile = $bannerFile['file'];
                if ($bannerFile ) {
                    $bannerName = time() . '-' . rand(1000, 9999) . '.' . $bannerFile->getClientOriginalExtension();
                    $bannerFile->move(public_path('company_file/inv_banner/'), $bannerName);
                    $newBanners[] = 'company_file/inv_banner/' . $bannerName;
                }
            }
        }

        $company = Company::create([
            'company_name'    => $request->company_name,
            'email'           => $request->email,
            'mobile'          => $request->mobile,
            'address'         => $request->address,
            'city'            => $request->city,
            'pin'             => $request->pin,
            'gstin'           => $request->gstin,
            'iec'             => $request->iec,
            'invoice_series'  => $request->invoice_series,
            'bank_name'       => $request->bank_name,
            'bank_branch'     => $request->bank_branch,
            'bank_ifsc'       => $request->bank_ifsc,
            'bank_account_no' => $request->bank_account_no,
            'ad_code'         => $request->adcode,
            'seller_id'       => Auth::id(),
            'status'          => Status::moduleStatusId('Company', 'active'),
            'logo'            => $upload_logo,
            'sign'            => $upload_sign,
            'bank_qr'         => $upload_bank_qr,
            'brand_banner'    => $newBanners,
        ]);

        if ($company) {
            return redirect()->route('company.index')->with('success', 'Company created successfully!');
        }

        return redirect()->route('company.index')->withErrors($valid);
    }

    public function delete_company($id){
        $company = Company::find($id);
        $company->delete();
        return redirect()->route('company.view')->with('success', 'Company deleted successfully!');
    }

    public function lut_commpany(Request $request,$id){
        $valid = $request->validate([
            'lut_no' => 'required',
            'starting_bill_no' => 'nullable',
            'expiry_date' => 'required',
        ]);
        if ($valid) {
          $lut = CompanyLUT::create([
                'company_id'=>$id,
                'status'=>Status::moduleStatusId('Company','active'),
                'expiry_date'=>$request->expiry_date,
                'lut_no'=>$request->lut_no,
                'starting_bill_count'=>$request->starting_bill_no,
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


    public function update_company(Request $request, $id)
    {
        $valid = $request->validate([
            'company_name'      => 'required|string|max:255',
            'email'             => 'nullable|email',
            'mobile'            => 'required',
            'address'           => 'required|string',
            'city'              => 'required|string',
            'pin'               => 'required',
            'gstin'             => 'required',
            'iec'               => 'required',
            'invoice_series'    => 'required',
            'bank_name'         => 'required',
            'bank_branch'       => 'required',
            'bank_ifsc'         => 'required',
            'bank_account_no'   => 'required',
            'adcode'            => 'required',
            'brand_banner.*'    => 'nullable', // validate each file
        ]);

        $company = Company::findOrFail($id);

        // Existing banners array
        $existingBanners = [];

        // Only decode if it's a string
        if (!empty($company->brand_banner)) {
            if (is_string($company->brand_banner)) {
                $existingBanners = json_decode($company->brand_banner, true) ?? [];
            } elseif (is_array($company->brand_banner)) {
                $existingBanners = $company->brand_banner; // already an array
            }
        }


        // New banners array
        $newBanners = [];

        // Handle Brand Banners
        if ($request->brand_banner) {
            // Delete old banners if any
            if (!empty($existingBanners)) {
                foreach ($existingBanners as $oldBanner) {
                    $oldBannerPath = public_path($oldBanner);
                    if (File::exists($oldBannerPath)) {
                        File::delete($oldBannerPath);
                    }
                }
            }

            // Upload new banners
            foreach ($request->brand_banner as $bannerFile) {
                $bannerFile = $bannerFile['file'];
                if ($bannerFile ) {
                    $bannerName = time() . '-' . rand(1000, 9999) . '.' . $bannerFile->getClientOriginalExtension();
                    $bannerFile->move(public_path('company_file/inv_banner/'), $bannerName);
                    $newBanners[] = 'company_file/inv_banner/' . $bannerName;
                }
            }
        } else {
            // Keep existing banners if no new files uploaded
            $newBanners = $existingBanners;
        }

        // Handle Logo
        if ($request->hasFile('logo')) {
            if ($company->logo && File::exists(public_path($company->logo))) {
                File::delete(public_path($company->logo));
            }
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('company_file/logo/'), $logoName);
            $company->logo = 'company_file/logo/' . $logoName;
        }

        // Handle Sign
        if ($request->hasFile('sign')) {
            if ($company->sign && File::exists(public_path($company->sign))) {
                File::delete(public_path($company->sign));
            }
            $sign = $request->file('sign');
            $signName = time() . '.' . $sign->getClientOriginalExtension();
            $sign->move(public_path('company_file/sign/'), $signName);
            $company->sign = 'company_file/sign/' . $signName;
        }

        // Handle Bank QR
        if ($request->hasFile('bank_qr')) {
            // Delete old file if exists
            if ($company->bank_qr && Storage::disk('public')->exists(str_replace('storage/', '', $company->bank_qr))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $company->bank_qr));
            }

            $bank_qr = $request->file('bank_qr');
            $bankQRName = time() . '.' . $bank_qr->getClientOriginalExtension();

            // Save in storage/app/public/company_file/bank_qr/
            $path = $bank_qr->storeAs('company_file/bank_qr', $bankQRName, 'public');

            // Save web-accessible path in DB
            $company->bank_qr = 'storage/' . $path;
        }

        // Update all fields
        $company->update([
            'company_name'      => $request->company_name,
            'email'             => $request->email,
            'mobile'            => $request->mobile,
            'address'           => $request->address,
            'city'              => $request->city,
            'pin'               => $request->pin,
            'gstin'             => $request->gstin,
            'iec'               => $request->iec,
            'invoice_series'    => $request->invoice_series,
            'bank_name'         => $request->bank_name,
            'bank_branch'       => $request->bank_branch,
            'bank_ifsc'         => $request->bank_ifsc,
            'bank_account_no'   => $request->bank_account_no,
            'ad_code'           => $request->adcode,
            'seller_id'         => Auth::id(),
            'brand_banner'      => $newBanners, // encode array to JSON
        ]);

        return redirect()->route('company.view')->with('success', 'Company updated successfully!');
    }

}
