<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoice_type_label }} - {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #111;
            margin: 0;
            padding: 0;
        }
        .invoice-box {
            border: 1.5px solid #000;
            width: 100%;
        }
        .title-bar {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            padding: 6px;
            background-color: #f2f2f2;
            border-bottom: 1px solid #000;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            vertical-align: top;
            padding: 4px 6px;
        }
        .border-bottom {
            border-bottom: 1px solid #000;
        }
        .border-right {
            border-right: 1px solid #000;
        }
        .border-top {
            border-top: 1px solid #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .meta-label {
            font-size: 8px;
            color: #555;
            text-transform: uppercase;
        }
        .company-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .logo-img {
            max-height: 50px;
            max-width: 120px;
        }
        .items-table {
            border-collapse: collapse;
            width: 100%;
        }
        .items-table th {
            background-color: #f8f9fa;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
            font-size: 9px;
            text-align: center;
        }
        .items-table th:last-child {
            border-right: none;
        }
        .items-table td {
            border-right: 1px solid #000;
            border-bottom: 1px solid #eee;
            font-size: 9px;
        }
        .items-table td:last-child {
            border-right: none;
        }
        .total-row td {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            font-weight: bold;
            background-color: #fafafa;
        }
        .footer-section {
            font-size: 8.5px;
        }
        .bank-title {
            font-weight: bold;
            border-bottom: 1px dotted #000;
            margin-bottom: 4px;
            padding-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <!-- Header Title -->
        <div class="title-bar">
            {{ strtoupper($invoice_type_label) }}
        </div>

        <!-- Seller and Metadata Section -->
        <table class="border-bottom">
            <tr>
                <!-- Seller Details -->
                <td width="50%" class="border-right">
                    <table>
                        <tr>
                            @if(!empty($logoBase64))
                            <td width="60" style="padding-right: 8px;">
                                <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo">
                            </td>
                            @endif
                            <td>
                                <div class="company-name">{{ $invoice->Company->company_name ?? '' }}</div>
                                <div>{{ $invoice->Company->address ?? '' }}</div>
                                <div>GSTIN/UIN: <strong>{{ $invoice->Company->gstin ?? 'N/A' }}</strong></div>
                                <div>State: {{ $sellerStateName }}, Code: {{ $sellerStateCode }}</div>
                                <div>Email: {{ $invoice->Company->email ?? '' }}</div>
                                <div>Phone: {{ $invoice->Company->mobile ?? '' }}</div>
                            </td>
                        </tr>
                    </table>
                </td>

                <!-- Invoice Meta Details -->
                <td width="50%" style="padding: 0;">
                    <table>
                        <tr class="border-bottom">
                            <td width="50%" class="border-right">
                                <span class="meta-label">Invoice No.</span><br>
                                <strong>{{ $invoice->invoice_number }}</strong>
                            </td>
                            <td width="50%">
                                <span class="meta-label">Dated</span><br>
                                <strong>{{ !empty($invoice->invoice_date) ? date('d-M-Y', strtotime($invoice->invoice_date)) : '-' }}</strong>
                            </td>
                        </tr>
                        @if($invoice_type === 'export')
                        <tr class="border-bottom">
                            <td width="50%" class="border-right">
                                <span class="meta-label">LUT No.</span><br>
                                {{ $invoice->lut->lut_no ?? 'N/A' }}
                            </td>
                            <td width="50%">
                                <span class="meta-label">LUT Date</span><br>
                                {{ $invoice->lut->formatted_expiry_date ?? 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="meta-label">IEC Code</span><br>
                                {{ $invoice->Company->iec ?? 'N/A' }}
                            </td>
                        </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>

        <!-- Buyer Details Section -->
        <table class="border-bottom">
            <tr>
                <td width="50%" class="border-right">
                    <span class="meta-label">Buyer (Bill & Ship To)</span><br>
                    <div class="bold" style="font-size: 11px;">{{ $invoice->Customer->company_name ?? ($invoice->Customer->name ?? 'Customer') }}</div>
                    <div>{{ $invoice->Customer->address ?? '' }}</div>
                    <div>GST/TPIN/TIN: <strong>{{ $invoice->Customer->gstin ?? 'No ID/TPIN/TIN NO' }}</strong></div>
                    <div>State: {{ $buyerStateName }}, Code: {{ $buyerStateCode }}</div>
                    <div>Phone: <strong>{{ $invoice->Customer->mobile ?? '' }}</strong></div>
                </td>
                <td width="50%">
                    @if(!empty($invoice->no_packets) || !empty($invoice->vehicle_no) || !empty($invoice->dispatched_through) || !empty($invoice->total_weight))
                    <table>
                        <tr>
                            <td width="50%" class="border-right">
                                <span class="meta-label">Total Weight</span><br>
                                {{ $invoice->total_weight ?? '-' }}
                            </td>
                            <td width="50%">
                                <span class="meta-label">No. of Packages</span><br>
                                {{ $invoice->no_packets ?? '-' }}
                            </td>
                        </tr>
                        <tr class="border-top">
                            <td width="50%" class="border-right">
                                <span class="meta-label">Dispatched Through</span><br>
                                {{ $invoice->dispatched_through ?? '-' }}
                            </td>
                            <td width="50%">
                                <span class="meta-label">Vehicle No.</span><br>
                                {{ $invoice->vehicle_no ?? '-' }}
                            </td>
                        </tr>
                    </table>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">Sl.</th>
                    <th width="{{ $invoice_type === 'gst' ? '30%' : '45%' }}" class="text-left">Description of Goods</th>
                    <th width="12%">HSN/SAC</th>
                    <th width="8%">Qty</th>
                    <th width="8%">Unit</th>
                    <th width="12%" class="text-right">Rate</th>
                    @if($invoice_type === 'gst')
                    <th width="12%" class="text-right">Taxable</th>
                    <th width="13%" class="text-right">GST</th>
                    @endif
                    <th width="13%" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $itemsList = $invoice->invoiceitems ?? ($invoice->items ?? []);
                    $totalQty = 0;
                    $totalTaxable = 0;
                    $totalGst = 0;
                    $grandTotal = 0;
                @endphp
                @foreach($itemsList as $index => $item)
                @php
                    $qty = floatval($item->quantity ?? 0);
                    $rate = floatval($item->rate ?? 0);
                    $taxable = $qty * $rate;
                    $gstPct = floatval($item->gst_percentage ?? 0);
                    $gstAmt = floatval($item->gst_amount ?? ($taxable * $gstPct / 100));
                    $rowTotal = ($invoice_type === 'gst') ? ($taxable + $gstAmt) : $taxable;

                    $totalQty += $qty;
                    $totalTaxable += $taxable;
                    $totalGst += $gstAmt;
                    $grandTotal += $rowTotal;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left"><strong>{{ $item->desc_product ?? ($item->product_name ?? '') }}</strong></td>
                    <td class="text-center">{{ $item->hsn_code ?? '-' }}</td>
                    <td class="text-center"><strong>{{ $qty }}</strong></td>
                    <td class="text-center">{{ $item->unit ?? 'PCS' }}</td>
                    <td class="text-right">{{ number_format($rate, 2) }}</td>
                    @if($invoice_type === 'gst')
                    <td class="text-right">{{ number_format($taxable, 2) }}</td>
                    <td class="text-right">{{ number_format($gstAmt, 2) }} ({{ $gstPct }}%)</td>
                    @endif
                    <td class="text-right">{{ number_format($rowTotal, 2) }}</td>
                </tr>
                @endforeach

                <!-- Totals Row -->
                <tr class="total-row">
                    <td colspan="3" class="text-right">TOTAL:</td>
                    <td class="text-center">{{ $totalQty }}</td>
                    <td></td>
                    <td></td>
                    @if($invoice_type === 'gst')
                    <td class="text-right">&#8377; {{ number_format($totalTaxable, 2) }}</td>
                    <td class="text-right">&#8377; {{ number_format($totalGst, 2) }}</td>
                    @endif
                    @php
                        $finalTotal = floatval($invoice->total_ammount ?? $grandTotal);
                    @endphp
                    <td class="text-right">&#8377; {{ number_format($finalTotal, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Amount in Words -->
        <table class="border-bottom">
            <tr>
                <td style="padding: 6px;">
                    <span class="meta-label">Amount Chargeable (in words):</span><br>
                    <strong>INR {{ $amountInWords }}</strong>
                </td>
            </tr>
        </table>

        <!-- Footer Section (Bank Details + Declaration & Signature) -->
        <table class="footer-section">
            <tr>
                <!-- Bank Details & Declaration -->
                <td width="60%" class="border-right" style="padding: 6px;">
                    <div class="bank-title">Company's Bank Details</div>
                    <div>Bank Name: <strong>{{ $invoice->Company->bank_name ?? '-' }}</strong></div>
                    <div>A/c No.: <strong>{{ $invoice->Company->bank_account_no ?? '-' }}</strong></div>
                    <div>Branch & IFSC: <strong>{{ $invoice->Company->bank_branch ?? 'Main' }} & {{ $invoice->Company->bank_ifsc ?? '-' }}</strong></div>
                    <br>
                    <div><u>Declaration:</u></div>
                    <div style="font-size: 7.5px; color: #444;">
                        We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.
                        Subject to {{ $invoice->Company->city ?? 'Local' }} Jurisdiction.
                    </div>
                </td>

                <!-- Signature Block -->
                <td width="40%" class="text-center" style="padding: 6px; vertical-align: bottom;">
                    <div class="bold">for {{ $invoice->Company->company_name ?? '' }}</div>
                    <div style="height: 45px; margin: 5px 0;">
                        @if(!empty($signBase64))
                        <img src="{{ $signBase64 }}" style="max-height: 40px;" alt="Signature">
                        @endif
                    </div>
                    <div class="border-top" style="padding-top: 3px; font-size: 8px;">
                        Authorized Signatory
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
