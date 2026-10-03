<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Mail\BillMail;
use App\Models\GSTInvoice;
use App\Models\Invoice;
use App\Models\PerformaInvoice;
use App\Models\PlainBill;
use Barryvdh\DomPDF\Facade\Pdf;
use Throwable;

class MailController extends Controller
{
    public function billmail(Request $request)
    {
        $invoice = Invoice::with(['Customer', 'Company.CompanyLut', 'invoiceitems', 'lut'])->find($request->invoice_id);

        if (!$invoice) {
            return redirect()->back()->with('error', 'Export invoice not found.');
        }

        $customer = $invoice->Customer;
        if (!$customer || empty($customer->email)) {
            return redirect()->back()->with('error', 'Customer email address is missing for this invoice.');
        }

        $invoiceTypeLabel = 'Export Invoice';
        $subject = "Export Invoice #{$invoice->invoice_number} from " . ($invoice->Company->company_name ?? 'ITrove Bills');
        $invoiceUrl = route('view.invoice', ['invoice_id' => Crypt::encrypt($invoice->id)]);

        $data = [
            "email" => $invoice->Company->email ?? config('mail.from.address'),
            "invoice_id" => $invoice->id,
            "invoice_url" => $invoiceUrl,
            "invoice_type_label" => $invoiceTypeLabel,
            "subject" => $subject,
            "name" => $customer->company_name ?? $customer->name ?? 'Customer',
            "billdate" => !empty($invoice->invoice_date) ? date('d-M-Y', strtotime($invoice->invoice_date)) : '-',
            "Seller_Company" => $invoice->Company->company_name ?? 'Company',
            "company_id" => $customer->id,
            "invoice_number" => $invoice->invoice_number,
        ];

        try {
            $pdfContent = $this->generateInvoicePdf($invoice, $invoiceTypeLabel, 'export');
            $pdfFilename = "Export_Invoice_{$invoice->invoice_number}.pdf";
            $companyEmails = $this->getCompanyCcEmails($invoice->Company);

            $mailable = Mail::to($customer->email);
            if (!empty($companyEmails)) {
                $mailable->cc($companyEmails);
            }
            $mailable->send(new BillMail($data, $pdfContent, $pdfFilename));

            return redirect()->back()->with('success', 'Mail Sent Successfully');
        } catch (Throwable $e) {
            Log::error('Error sending bill mail: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to send mail: ' . $e->getMessage());
        }
    }

    public function performa_billmail(Request $request)
    {
        $invoice = PerformaInvoice::with(['Customer', 'Company.CompanyLut', 'invoiceitems', 'lut'])->find($request->invoice_id);

        if (!$invoice) {
            return redirect()->back()->with('error', 'Proforma invoice not found.');
        }

        $customer = $invoice->Customer;
        if (!$customer || empty($customer->email)) {
            return redirect()->back()->with('error', 'Customer email address is missing for this invoice.');
        }

        $invoiceTypeLabel = 'Proforma Invoice';
        $subject = "Proforma Invoice #{$invoice->invoice_number} from " . ($invoice->Company->company_name ?? 'ITrove Bills');
        $invoiceUrl = route('performa.view.invoice', ['invoice_id' => Crypt::encrypt($invoice->id)]);

        $data = [
            "email" => $invoice->Company->email ?? config('mail.from.address'),
            "invoice_id" => $invoice->id,
            "invoice_url" => $invoiceUrl,
            "invoice_type_label" => $invoiceTypeLabel,
            "subject" => $subject,
            "name" => $customer->company_name ?? $customer->name ?? 'Customer',
            "billdate" => !empty($invoice->invoice_date) ? date('d-M-Y', strtotime($invoice->invoice_date)) : '-',
            "Seller_Company" => $invoice->Company->company_name ?? 'Company',
            "company_id" => $customer->id,
            "invoice_number" => $invoice->invoice_number,
        ];

        try {
            $pdfContent = $this->generateInvoicePdf($invoice, $invoiceTypeLabel, 'proforma');
            $pdfFilename = "Proforma_Invoice_{$invoice->invoice_number}.pdf";
            $companyEmails = $this->getCompanyCcEmails($invoice->Company);

            $mailable = Mail::to($customer->email);
            if (!empty($companyEmails)) {
                $mailable->cc($companyEmails);
            }
            $mailable->send(new BillMail($data, $pdfContent, $pdfFilename));

            return redirect()->back()->with('success', 'Mail Sent Successfully');
        } catch (Throwable $e) {
            Log::error('Error sending performa bill mail: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to send mail: ' . $e->getMessage());
        }
    }

    public function gst_billmail(Request $request)
    {
        $invoice = GSTInvoice::with(['Customer', 'Company.CompanyLut', 'invoiceitems', 'lut'])->find($request->invoice_id);

        if (!$invoice) {
            return redirect()->back()->with('error', 'GST invoice not found.');
        }

        $customer = $invoice->Customer;
        if (!$customer || empty($customer->email)) {
            return redirect()->back()->with('error', 'Customer email address is missing for this invoice.');
        }

        $invoiceTypeLabel = 'GST Invoice';
        $subject = "GST Invoice #{$invoice->invoice_number} from " . ($invoice->Company->company_name ?? 'ITrove Bills');
        $invoiceUrl = route('gst.view.invoice', ['invoice_id' => Crypt::encrypt($invoice->id)]);

        $data = [
            "email" => $invoice->Company->email ?? config('mail.from.address'),
            "invoice_id" => $invoice->id,
            "invoice_url" => $invoiceUrl,
            "invoice_type_label" => $invoiceTypeLabel,
            "subject" => $subject,
            "name" => $customer->company_name ?? $customer->name ?? 'Customer',
            "billdate" => !empty($invoice->invoice_date) ? date('d-M-Y', strtotime($invoice->invoice_date)) : '-',
            "Seller_Company" => $invoice->Company->company_name ?? 'Company',
            "company_id" => $customer->id,
            "invoice_number" => $invoice->invoice_number,
        ];

        try {
            $pdfContent = $this->generateInvoicePdf($invoice, $invoiceTypeLabel, 'gst');
            $pdfFilename = "GST_Invoice_{$invoice->invoice_number}.pdf";
            $companyEmails = $this->getCompanyCcEmails($invoice->Company);

            $mailable = Mail::to($customer->email);
            if (!empty($companyEmails)) {
                $mailable->cc($companyEmails);
            }
            $mailable->send(new BillMail($data, $pdfContent, $pdfFilename));

            return redirect()->back()->with('success', 'Mail Sent Successfully');
        } catch (Throwable $e) {
            Log::error('Error sending GST bill mail: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to send mail: ' . $e->getMessage());
        }
    }

    public function plain_billmail(Request $request)
    {
        $invoice = PlainBill::with(['Customer', 'Company.CompanyLut', 'items', 'lut'])->find($request->invoice_id);

        if (!$invoice) {
            return redirect()->back()->with('error', 'Plain bill not found.');
        }

        $customer = $invoice->Customer;
        if (!$customer || empty($customer->email)) {
            return redirect()->back()->with('error', 'Customer email address is missing for this invoice.');
        }

        $invoiceTypeLabel = 'Plain Bill';
        $subject = "Bill #{$invoice->invoice_number} from " . ($invoice->Company->company_name ?? 'ITrove Bills');
        $invoiceUrl = route('book.view.invoice', ['invoice_id' => Crypt::encrypt($invoice->id)]);

        $data = [
            "email" => $invoice->Company->email ?? config('mail.from.address'),
            "invoice_id" => $invoice->id,
            "invoice_url" => $invoiceUrl,
            "invoice_type_label" => $invoiceTypeLabel,
            "subject" => $subject,
            "name" => $customer->company_name ?? $customer->name ?? 'Customer',
            "billdate" => !empty($invoice->invoice_date) ? date('d-M-Y', strtotime($invoice->invoice_date)) : '-',
            "Seller_Company" => $invoice->Company->company_name ?? 'Company',
            "company_id" => $customer->id,
            "invoice_number" => $invoice->invoice_number,
        ];

        try {
            $pdfContent = $this->generateInvoicePdf($invoice, $invoiceTypeLabel, 'plain');
            $pdfFilename = "Bill_{$invoice->invoice_number}.pdf";
            $companyEmails = $this->getCompanyCcEmails($invoice->Company);

            $mailable = Mail::to($customer->email);
            if (!empty($companyEmails)) {
                $mailable->cc($companyEmails);
            }
            $mailable->send(new BillMail($data, $pdfContent, $pdfFilename));

            return redirect()->back()->with('success', 'Mail Sent Successfully');
        } catch (Throwable $e) {
            Log::error('Error sending plain bill mail: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to send mail: ' . $e->getMessage());
        }
    }

    private function getCompanyCcEmails($company)
    {
        $emails = [];
        if ($company && !empty($company->email)) {
            $parts = explode(',', $company->email);
            foreach ($parts as $part) {
                $trimmed = trim($part);
                if (filter_var($trimmed, FILTER_VALIDATE_EMAIL)) {
                    $emails[] = $trimmed;
                }
            }
        }
        return array_unique($emails);
    }

    private function generateInvoicePdf($invoice, string $invoiceTypeLabel, string $invoiceType)
    {
        $logoBase64 = $this->imageToBase64($invoice->Company->logo ?? null);
        $signBase64 = $this->imageToBase64($invoice->Company->sign ?? null);

        list($sellerStateCode, $sellerStateName) = $this->getStateDetails($invoice->Company->gstin ?? null);
        list($buyerStateCode, $buyerStateName) = $this->getStateDetails($invoice->Customer->gstin ?? null);

        $totalAmt = $invoice->total_ammount ?? 0;
        if ($invoiceType === 'gst' && method_exists($invoice, 'calculateRoundedTotal')) {
            $totalAmt = GSTInvoice::calculateRoundedTotal($invoice->subtotal_amount ?? 0);
        }
        $amountInWords = $this->amountInWords($totalAmt);

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'invoice_type' => $invoiceType,
            'invoice_type_label' => $invoiceTypeLabel,
            'logoBase64' => $logoBase64,
            'signBase64' => $signBase64,
            'sellerStateCode' => $sellerStateCode,
            'sellerStateName' => $sellerStateName,
            'buyerStateCode' => $buyerStateCode,
            'buyerStateName' => $buyerStateName,
            'amountInWords' => $amountInWords,
        ])->setPaper('a4', 'portrait');

        return $pdf->output();
    }

    private function getStateDetails($gstin)
    {
        $stateCodes = [
            '01' => 'Jammu & Kashmir', '02' => 'Himachal Pradesh', '03' => 'Punjab',
            '04' => 'Chandigarh', '05' => 'Uttarakhand', '06' => 'Haryana',
            '07' => 'Delhi', '08' => 'Rajasthan', '09' => 'Uttar Pradesh',
            '10' => 'Bihar', '11' => 'Sikkim', '12' => 'Arunachal Pradesh',
            '13' => 'Nagaland', '14' => 'Manipur', '15' => 'Mizoram',
            '16' => 'Tripura', '17' => 'Meghalaya', '18' => 'Assam',
            '19' => 'West Bengal', '20' => 'Jharkhand', '21' => 'Odisha',
            '22' => 'Chhattisgarh', '23' => 'Madhya Pradesh', '24' => 'Gujarat',
            '25' => 'Daman & Diu', '26' => 'Dadra & Nagar Haveli', '27' => 'Maharashtra',
            '28' => 'Andhra Pradesh (Old)', '29' => 'Karnataka', '30' => 'Goa',
            '31' => 'Lakshadweep', '32' => 'Kerala', '33' => 'Tamil Nadu',
            '34' => 'Puducherry', '35' => 'Andaman & Nicobar Islands', '36' => 'Telangana',
            '37' => 'Andhra Pradesh (New)', '38' => 'Ladakh'
        ];
        $code = !empty($gstin) && strlen($gstin) >= 2 ? substr($gstin, 0, 2) : 'N/A';
        $name = $stateCodes[$code] ?? 'Other State';
        return [$code, $name];
    }

    private function imageToBase64($path)
    {
        if (!empty($path) && file_exists(public_path($path))) {
            $type = pathinfo(public_path($path), PATHINFO_EXTENSION);
            $data = file_get_contents(public_path($path));
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return null;
    }

    private function amountInWords($amount)
    {
        $number = floatval($amount);
        $no = floor($number);
        $point = round(($number - $no) * 100);
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '', '1' => 'One', '2' => 'Two',
            '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
            '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
            '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
            '13' => 'Thirteen', '14' => 'Fourteen',
            '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
            '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
            '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
            '60' => 'Sixty', '70' => 'Seventy', '80' => 'Eighty',
            '90' => 'Ninety'
        );
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : '';
                $hundred = ($counter == 1 && !empty($str[0])) ? ' and ' : '';
                $str[] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred
                    : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
            } else {
                $str[] = null;
            }
        }
        $rupees = implode('', array_reverse(array_filter($str)));
        $paise = ($point > 0 && isset($words[floor($point / 10) * 10])) ? "and " . ($words[floor($point / 10) * 10] . " " . ($words[$point % 10] ?? '')) . ' Paise' : '';
        $result = trim(($rupees ? $rupees . 'Rupees ' : '') . $paise);
        return empty($result) ? 'Zero Rupees Only' : $result . ' Only';
    }

    public static function sendInvoiceMailByModel($invoice, string $type = 'regular', array $paymentDetails = [])
    {
        if (!$invoice) {
            return false;
        }

        if ($type === 'gst' || $invoice instanceof GSTInvoice) {
            $invoiceTypeLabel = 'GST Invoice';
            $pdfType = 'gst';
            $invoiceUrl = route('gst.view.invoice', ['invoice_id' => Crypt::encrypt($invoice->id)]);
            $itemsRelation = 'invoiceitems';
        } elseif ($type === 'proforma' || $invoice instanceof PerformaInvoice) {
            $invoiceTypeLabel = 'Proforma Invoice';
            $pdfType = 'proforma';
            $invoiceUrl = route('performa.view.invoice', ['invoice_id' => Crypt::encrypt($invoice->id)]);
            $itemsRelation = 'invoiceitems';
        } elseif ($type === 'plain' || $invoice instanceof PlainBill) {
            $invoiceTypeLabel = 'Plain Bill';
            $pdfType = 'plain';
            $invoiceUrl = route('book.view.invoice', ['invoice_id' => Crypt::encrypt($invoice->id)]);
            $itemsRelation = 'items';
        } else {
            $invoiceTypeLabel = 'Export Invoice';
            $pdfType = 'export';
            $invoiceUrl = route('view.invoice', ['invoice_id' => Crypt::encrypt($invoice->id)]);
            $itemsRelation = 'invoiceitems';
        }

        $invoice->loadMissing(['Customer', 'Company.CompanyLut', $itemsRelation, 'lut']);

        $customer = $invoice->Customer;
        if (!$customer || empty($customer->email)) {
            Log::warning("Customer email address missing for invoice ID: {$invoice->id}");
            return false;
        }

        $isPayment = !empty($paymentDetails['is_payment_notification']);
        if ($isPayment) {
            $subject = "Payment Receipt for {$invoiceTypeLabel} #{$invoice->invoice_number} - Amount Paid: ₹" . ($paymentDetails['paid_now'] ?? '0.00');
        } else {
            $subject = "{$invoiceTypeLabel} #{$invoice->invoice_number} from " . ($invoice->Company->company_name ?? 'ITrove Bills');
        }

        $data = array_merge([
            "email" => $invoice->Company->email ?? config('mail.from.address'),
            "invoice_id" => $invoice->id,
            "invoice_url" => $invoiceUrl,
            "invoice_type_label" => $invoiceTypeLabel,
            "subject" => $subject,
            "name" => $customer->company_name ?? $customer->name ?? 'Customer',
            "billdate" => !empty($invoice->invoice_date) ? date('d-M-Y', strtotime($invoice->invoice_date)) : '-',
            "Seller_Company" => $invoice->Company->company_name ?? 'Company',
            "company_id" => $customer->id,
            "invoice_number" => $invoice->invoice_number,
            "is_payment_notification" => $isPayment,
        ], $paymentDetails);

        $controller = new self();
        $pdfContent = $controller->generateInvoicePdf($invoice, $invoiceTypeLabel, $pdfType);
        $pdfFilename = str_replace(' ', '_', $invoiceTypeLabel) . "_{$invoice->invoice_number}.pdf";
        $companyEmails = $controller->getCompanyCcEmails($invoice->Company);

        $mailable = Mail::to($customer->email);
        if (!empty($companyEmails)) {
            $mailable->cc($companyEmails);
        }
        $mailable->send(new BillMail($data, $pdfContent, $pdfFilename));

        return true;
    }
}
