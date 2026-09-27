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
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            font-family: Arial, sans-serif;
            border: 1px solid #e2e8f0;
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
            <p style="color: #4a5568; font-size: 14px;">
                You have received <strong>{{ $data['invoice_type_label'] }}</strong> <strong>#{{ $data['invoice_number'] }}</strong> generated on <strong>{{ $data['billdate'] }}</strong> from <strong>{{ $data['Seller_Company'] }}</strong>.
            </p>
            <p style="color: #4a5568; font-size: 14px;">
                Please find the attached PDF invoice for your reference, or click below to view your invoice online:
            </p>
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
