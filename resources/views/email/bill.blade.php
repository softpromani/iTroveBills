<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITrove Bills</title>
    <style>
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .header {
            margin-bottom: 20px;
        }

        .logo {
            max-width: 100px;
        }

        .content {
            margin-bottom: 20px;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }

        p {
            color: green;
        }

        a{
            text-decoration: none;
            border: 1px solid purple;
            padding: 10px;
            background-color: purple;
            color: white;
            border-radius: 10px;
            margin-bottom:20px;
        }
    </style>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif;">

    <div class="container">

        <!-- Header with Logo -->
        <div class="header">
            <!-- Logo -->
            <img src="{{ asset('itimages/itlogo.png') }}" alt="Company Logo" class="logo">
            <h3>{{ env('APP_NAME') }}</h3>
        </div>

        <!-- Content -->
        <div class="content">
            <h4> Hii {{ $data['name'] }}
                <br />
                You have receive Invoice {{ $data['invoice_number'] }} generate on {{ $data['billdate'] }} from
                {{ $data['Seller_Company'] }}
                Please Download your bill from here -
            </h4>
            <a href="{{ url('/view-invoice') }}?invoice_id={{ encrypt($data['invoice_id']) }}">
               See Your Bill
            </a>
            <br />
            <h4>
                Thank You
            </h4>


            <br/><br/><br/>
            <p><a href="{{ route('register', ['company_id' => Crypt::encrypt($data['company_id']) ]) }}">Create an account on ITrove Bill</a></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2023 InnovationTrove. All rights reserved.</p>
        </div>

    </div>

</body>

</html>
