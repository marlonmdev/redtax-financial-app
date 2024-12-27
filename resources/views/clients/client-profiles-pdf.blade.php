<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RED Tax Financial Services Client List</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji",
            "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
        table {
            width: 1024px;
            border-collapse: collapse;
        }
        td, th {
            padding: 3px 6px;
            text-align: left;
            font-size: 0.875rem;
        }
        tr{
            border-bottom: 1px solid #ccc;
        }
        th {
            background: #fff;
            border: none;
            font-weight: medium;
        }
        #heading-title {
            text-align: center;
            margin-bottom: 20px;
        }
        
        #heading-title h2, 
        #heading-title h3 {
            margin: 0;
        }
        
        @media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px) {
            table {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: .625rem;">
        <img src="{{ asset('assets/images/redtax-logo.png') }}" width="180" height="auto" alt="RED Tax Financial Services Logo" style="display: block; margin: 0 auto;">
    </div>
    
    <div id="heading-title">
        <h2>RED Tax Financial Services Client List</h2>
        <h3>{{ $currentDateTime }}</h3>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Date Added</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Zip Code</th>
                <th>Customer Type</th>
                <th>Referrer</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{ date('m/d/Y', strtotime($client['latest_added_on'])) }}</td>
                    <td>{{ $client['name'] }}</td>
                    <td>{{ $client['email'] }}</td>
                    <td>{{ $client['phone'] }}</td>
                    <td>{{ $client['address'] }}</td>
                    <td>{{ $client['city'] }}</td>
                    <td>{{ $client['state'] }}</td>
                    <td>{{ $client['zip_code'] }}</td>
                    <td>{{ $client['customer_type'] }}</td>
                    <td>{{ $client['referred_by'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>