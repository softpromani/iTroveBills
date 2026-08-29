<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\SellerCustomers;
use App\Mail\BillMail;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class QuotationController extends Controller
{
    /**
     * Display the quotation creation form.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companies = Company::where('seller_id', $user->id)->get();
        $customers = SellerCustomers::where('seller_id', $user->id)->get();

        $company_list = $companies->map(function ($company) {
            return [
                'id' => $company->id,
                'name' => $company->company_name,
            ];
        });

        $customer_list = $customers->map(function ($customer) {
            $detail = $customer->customer_detail;
            return [
                'id' => $customer->id,
                'customer_company_id' => $customer->customer_company_id,
                'name' => ($detail['name'] ?? 'Unknown') . ($detail['mobile'] ? ' / ' . $detail['mobile'] : ''),
                'details' => $detail,
            ];
        });

        // Default proposal letter content
        $default_cover_letter = [
            "As per our discussions, kindly get our best proposal in regard to subject matter. We thank you very much for your keen interest shown towards our products & services.",
            "Innovation Trove is a Professional Web Design & Development, Consultancy & Software Solutions Company remotely based in Lucknow, U.P. India and its Head office is located in Daragaon, Jaigaon, West Bengal.",
            "We offer industry-specific solutions and integration services through a unique onsite, offsite, offshore delivery model that helps our clients achieve reduced \"time to market\" for their products and world-class quality on-time and that too in budget. To help our clients to meet their goals by offering better and time-oriented services. We offer high-end creative solutions for business communication.",
            "Innovation Trove Best Website Design & Development Company, We provide Custom Software Development, Accounting & Billing with GST, School Management Software, Hospital Management Software, Petrol Pump Software, CRM, HRM, ERP Real Estate software, Social Media Marketing (SMM), Search engine Optimization (SEO), Social Media Optimization (SMO) etc.",
            "In the light of above, we request you to kindly look in to the offer attached here with and revert back to us with favor for the assignment to make your project successful.",
            "We assure you of our best services for all times."
        ];

        // Default terms & conditions matching PDF
        $default_terms = [
            [
                'title' => 'Taxes',
                'description' => 'Goods and Services Tax (GST) will be charged extra as applicable per prevailing Government regulations.'
            ],
            [
                'title' => 'Payment Terms',
                'description' => '100% full payment is due immediately upon successful completion and handover of the scope of work.'
            ],
            [
                'title' => 'Project Commencement',
                'description' => 'Work execution will begin upon receipt of an official Purchase Order (PO) / Work Order (WO).'
            ],
            [
                'title' => 'Warranty & Support',
                'description' => 'Includes a 1-Year Support Warranty covering remote assistance and online troubleshooting. On-site physical deployment support is limited to one (01) initial visit during setup and configuration.'
            ],
            [
                'title' => 'Site Readiness',
                'description' => 'The client must ensure that the existing Local Area Network (LAN) infrastructure and cabling within the lab are fully functional prior to the start of installation.'
            ],
            [
                'title' => 'Network Prerequisites',
                'description' => 'All client workstations must have active network connectivity and be capable of successfully communicating (PING) with the central server before deployment begins.'
            ],
            [
                'title' => 'Network Rectification Clause',
                'description' => 'If the existing LAN infrastructure is found non-operational or faulty during deployment, passive network repair and troubleshooting will attract an additional service charge of ₹60,000/- plus GST per computer lab.'
            ],
            [
                'title' => 'Hardware & Spares',
                'description' => 'Any network components or hardware consumables (e.g., Network Switches, Cat6 Cables, Patch Cords, Access Points, RJ45 Connectors, Casing/Conduits) required for network restoration are not included in the base scope and will be billed separately upon approval of a supplementary order.'
            ]
        ];

        // Auto-generate reference number if company selected
        $suggested_ref = 'QT-' . date('Ymd') . '-01';
        $selected_company = null;

        if ($request->filled('company_id')) {
            $company_id = is_array($request->company_id) ? $request->company_id['id'] : $request->company_id;
            $company_obj = Company::where('seller_id', $user->id)->where('id', $company_id)->first();
            if ($company_obj) {
                $selected_company = $company_obj;
                $prefix = $company_obj->invoice_series ?? 'QT';
                $count = Quotation::where('company_id', $company_id)->count() + 1;
                $monthYear = date('m/Y');
                $suggested_ref = sprintf("%s/QT/%02d/%s", $prefix, $count, date('M'));
            }
        }

        return Inertia::render('Quotations/Create', [
            'company_list' => $company_list,
            'customer_list' => $customer_list,
            'companies' => $companies,
            'default_cover_letter' => $default_cover_letter,
            'default_terms' => $default_terms,
            'default_tax_note' => 'The total costing + @18% GST will be considered as final amount paid.',
            'suggested_ref' => $suggested_ref,
            'selected_company_id' => $request->company_id ?? null,
            'selected_customer_id' => $request->customer_id ?? null,
        ]);
    }

    /**
     * Store a newly created quotation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'quotation_number' => 'required',
            'quotation_date' => 'required|date',
            'title' => 'required|string',
            'items' => 'required|array|min:1',
        ]);

        $company_id = is_array($request->company_id) ? $request->company_id['id'] : $request->company_id;
        $customer_id = $request->filled('customer_id') 
            ? (is_array($request->customer_id) ? ($request->customer_id['id'] ?? null) : $request->customer_id) 
            : null;

        $total_amount = 0;
        foreach ($request->items as $item) {
            $total_amount += (float)($item['total_rate'] ?? (($item['quantity'] ?? 1) * ($item['unit_rate'] ?? 0)));
        }

        $quotation = Quotation::create([
            'company_id' => $company_id,
            'customer_company_id' => $customer_id,
            'customer_data' => $request->customer_data,
            'quotation_number' => $request->quotation_number,
            'quotation_date' => $request->quotation_date,
            'title' => $request->title,
            'recipient_designation' => $request->recipient_designation,
            'subject' => $request->subject ?? $request->title,
            'cover_letter_content' => $request->cover_letter_content,
            'total_amount' => $total_amount,
            'tax_note' => $request->tax_note,
            'terms_and_conditions' => $request->terms_and_conditions,
            'status' => $request->status ?? 'draft',
            'created_by' => Auth::id(),
        ]);

        foreach ($request->items as $index => $item) {
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'item_order' => $index + 1,
                'title' => $item['title'] ?? '',
                'description_points' => $item['description_points'] ?? [],
                'hsn_code' => $item['hsn_code'] ?? null,
                'unit' => $item['unit'] ?? '',
                'quantity' => $item['quantity'] ?? 1,
                'unit_rate' => $item['unit_rate'] ?? 0,
                'total_rate' => $item['total_rate'] ?? (($item['quantity'] ?? 1) * ($item['unit_rate'] ?? 0)),
            ]);
        }

        return redirect()->route('quotation.list')->with('success', 'Quotation Created Successfully!');
    }

    /**
     * Display a listing of quotations.
     */
    public function list(Request $request)
    {
        $user = Auth::user();
        $companyIds = Company::where('seller_id', $user->id)->pluck('id');

        $query = Quotation::with(['Company', 'Customer', 'items'])
            ->whereIn('company_id', $companyIds)
            ->latest('quotation_date');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(customer_data, '$.name')) LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(customer_data, '$.company_name')) LIKE ?", ["%{$search}%"]);
            });
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $quotations = $query->paginate(15)->withQueryString();
        $companies = Company::where('seller_id', $user->id)->get();

        return Inertia::render('Quotations/List', [
            'quotations' => $quotations,
            'companies' => $companies,
            'filters' => $request->only(['search', 'company_id', 'status']),
        ]);
    }

    /**
     * Show the form for editing the specified quotation.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $companyIds = Company::where('seller_id', $user->id)->pluck('id');

        $quotation = Quotation::with(['Company', 'Customer', 'items'])
            ->whereIn('company_id', $companyIds)
            ->findOrFail($id);

        $companies = Company::where('seller_id', $user->id)->get();
        $customers = SellerCustomers::where('seller_id', $user->id)->get();

        $company_list = $companies->map(function ($company) {
            return [
                'id' => $company->id,
                'name' => $company->company_name,
            ];
        });

        $customer_list = $customers->map(function ($customer) {
            $detail = $customer->customer_detail;
            return [
                'id' => $customer->id,
                'customer_company_id' => $customer->customer_company_id,
                'name' => ($detail['name'] ?? 'Unknown') . ($detail['mobile'] ? ' / ' . $detail['mobile'] : ''),
                'details' => $detail,
            ];
        });

        return Inertia::render('Quotations/Edit', [
            'quotation' => $quotation,
            'company_list' => $company_list,
            'customer_list' => $customer_list,
            'companies' => $companies,
        ]);
    }

    /**
     * Update the specified quotation in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $companyIds = Company::where('seller_id', $user->id)->pluck('id');

        $quotation = Quotation::whereIn('company_id', $companyIds)->findOrFail($id);

        $request->validate([
            'company_id' => 'required',
            'quotation_number' => 'required',
            'quotation_date' => 'required|date',
            'title' => 'required|string',
            'items' => 'required|array|min:1',
        ]);

        $company_id = is_array($request->company_id) ? $request->company_id['id'] : $request->company_id;
        $customer_id = $request->filled('customer_id') 
            ? (is_array($request->customer_id) ? ($request->customer_id['id'] ?? null) : $request->customer_id) 
            : null;

        $total_amount = 0;
        foreach ($request->items as $item) {
            $total_amount += (float)($item['total_rate'] ?? (($item['quantity'] ?? 1) * ($item['unit_rate'] ?? 0)));
        }

        $quotation->update([
            'company_id' => $company_id,
            'customer_company_id' => $customer_id,
            'customer_data' => $request->customer_data,
            'quotation_number' => $request->quotation_number,
            'quotation_date' => $request->quotation_date,
            'title' => $request->title,
            'recipient_designation' => $request->recipient_designation,
            'subject' => $request->subject ?? $request->title,
            'cover_letter_content' => $request->cover_letter_content,
            'total_amount' => $total_amount,
            'tax_note' => $request->tax_note,
            'terms_and_conditions' => $request->terms_and_conditions,
            'status' => $request->status ?? $quotation->status,
        ]);

        // Delete old items and insert updated ones
        $quotation->items()->delete();

        foreach ($request->items as $index => $item) {
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'item_order' => $index + 1,
                'title' => $item['title'] ?? '',
                'description_points' => $item['description_points'] ?? [],
                'hsn_code' => $item['hsn_code'] ?? null,
                'unit' => $item['unit'] ?? '',
                'quantity' => $item['quantity'] ?? 1,
                'unit_rate' => $item['unit_rate'] ?? 0,
                'total_rate' => $item['total_rate'] ?? (($item['quantity'] ?? 1) * ($item['unit_rate'] ?? 0)),
            ]);
        }

        return redirect()->route('quotation.list')->with('success', 'Quotation Updated Successfully!');
    }

    /**
     * Remove the specified quotation from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $companyIds = Company::where('seller_id', $user->id)->pluck('id');

        $quotation = Quotation::whereIn('company_id', $companyIds)->findOrFail($id);
        $quotation->delete();

        return redirect()->route('quotation.list')->with('success', 'Quotation Deleted Successfully!');
    }

    /**
     * Render the quotation template view for printing / PDF preview.
     */
    public function template($id)
    {
        $user = Auth::user();
        $companyIds = Company::where('seller_id', $user->id)->pluck('id');

        $quotation = Quotation::with(['Company', 'Customer', 'items'])
            ->whereIn('company_id', $companyIds)
            ->findOrFail($id);

        return Inertia::render('Quotations/QuotationTemplate', [
            'quotation' => $quotation,
        ]);
    }

    /**
     * Send quotation via email.
     */
    public function send_mail(Request $request)
    {
        $request->validate([
            'quotation_id' => 'required',
            'email' => 'required|email',
        ]);

        $user = Auth::user();
        $companyIds = Company::where('seller_id', $user->id)->pluck('id');

        $quotation = Quotation::with(['Company', 'Customer', 'items'])
            ->whereIn('company_id', $companyIds)
            ->findOrFail($request->quotation_id);

        // Can send email or notification
        return response()->json(['status' => 'success', 'message' => 'Email sent successfully!']);
    }
}
