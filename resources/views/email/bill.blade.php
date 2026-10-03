<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['subject'] ?? 'Invoice' }}</title>
    <style>
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 25px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .header {
            margin-bottom: 20px;
            text-align: center;
        }

        .logo {
            max-width: 110px;
        }

        .content {
            margin-bottom: 25px;
            text-align: left;
            line-height: 1.6;
        }

        .payment-card {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 18px;
            margin: 20px 0;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .payment-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .payment-table tr:last-child td {
            border-bottom: none;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-green {
            color: #16a34a;
        }

        .text-red {
            color: #dc2626;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            font-size: 12px;
            color: #777;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .btn-view {
            display: inline-block;
            text-decoration: none;
            border: 1px solid #6b21a8;
            padding: 12px 24px;
            background-color: #6b21a8;
            color: #ffffff !important;
            border-radius: 6px;
            font-weight: bold;
            margin: 15px 0;
            font-size: 14px;
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f5f7; font-family: Arial, sans-serif;">

    <div class="container">

        <!-- Header with Logo -->
        <div class="header">
            <div style="display: inline-block; position: relative; vertical-align: top;">
                <img src="{{ asset('itimages/itlogo.png') }}" alt="Company Logo" class="logo">
            </div>
            <h3 style="margin-top: 10px; color: #333; font-size: 18px;">{{ env('APP_NAME', 'ITrove Bills') }}</h3>
        </div>

        <!-- Content -->
        <div class="content">
            <h4 style="color: #1a202c; font-size: 16px; margin-bottom: 12px;">Hi {{ $data['name'] }},</h4>

            @if(!empty($data['is_payment_notification']))
                <p style="color: #4a5568; font-size: 14px;">
                    Thank you for your payment! A payment of <strong class="text-green">₹{{ $data['paid_now'] }}</strong> has been successfully received for <strong>{{ $data['invoice_type_label'] }} #{{ $data['invoice_number'] }}</strong> from <strong>{{ $data['Seller_Company'] }}</strong>.
                </p>

                <div class="payment-card">
                    <h5 style="margin-top: 0; margin-bottom: 12px; color: #1e293b; font-size: 15px;">Payment Details</h5>
                    <table class="payment-table">
                        <tr>
                            <td class="font-bold">Invoice Number:</td>
                            <td>#{{ $data['invoice_number'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Payment Method:</td>
                            <td>{{ $data['payment_mode'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Reference Number:</td>
                            <td>{{ $data['reference_no'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold text-green">Amount Paid Now:</td>
                            <td class="font-bold text-green">₹{{ $data['paid_now'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Total Invoice Amount:</td>
                            <td>₹{{ $data['total_amount'] }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Total Paid So Far:</td>
                            <td>₹{{ $data['total_paid'] }}</td>
                        </tr>
                        <tr style="background-color: #f1f5f9;">
                            <td class="font-bold text-red">Remaining Balance:</td>
                            <td class="font-bold text-red">₹{{ $data['remaining_balance'] }}</td>
                        </tr>
                        @if(!empty($data['remark']))
                        <tr>
                            <td class="font-bold">Remark:</td>
                            <td>{{ $data['remark'] }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                <p style="color: #4a5568; font-size: 14px;">
                    Please find the updated invoice PDF attached for your records, or click below to view your invoice online:
                </p>
            @else
                <p style="color: #4a5568; font-size: 14px;">
                    You have received <strong>{{ $data['invoice_type_label'] }}</strong> <strong>#{{ $data['invoice_number'] }}</strong> generated on <strong>{{ $data['billdate'] }}</strong> from <strong>{{ $data['Seller_Company'] }}</strong>.
                </p>
                <p style="color: #4a5568; font-size: 14px;">
                    Please find the attached PDF invoice for your reference, or click below to view your invoice online:
                </p>
            @endif

            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ $data['invoice_url'] }}" class="btn-view" target="_blank">
                   See Your {{ $data['invoice_type_label'] }}
                </a>
            </div>
            <p style="color: #4a5568; font-size: 14px; margin-top: 20px;">
                Thank You,<br>
                <strong>{{ $data['Seller_Company'] }}</strong>
            </p>

            <br/>
            <div style="text-align: center;">
                <a href="{{ route('register', ['company_id' => Crypt::encrypt($data['company_id']) ]) }}" style="color: #6b21a8; text-decoration: underline; font-size: 13px;">Create an account on ITrove Bills</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0;">&copy; {{ date('Y') }} InnovationTrove. All rights reserved.</p>
        </div>

    </div>

</body>

</html>
