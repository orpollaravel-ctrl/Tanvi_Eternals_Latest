<!DOCTYPE html>
<html>
<head>
    <title>Visits - Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        @media print {
            button {
                display: none;
            }
        }
        .print-btn {
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">Print</button>

<h1>Visits List</h1>

<table>
    <thead>
        <tr>
            <th>#</th> 
            <th>Customer</th> 
            <th>Phone</th>
            <th>Visit Date</th>
            <th>Time</th>
            <th>Reason</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($visits as $index => $visit)
            <tr>
                <td>{{ $index + 1 }}</td> 
                <td>{{ $visit->customer_name }}</td> 
                <td>{{ $visit->phone }}</td>
                <td>{{ \Carbon\Carbon::parse($visit->target_date)->format('d-m-Y') }}</td>
                <td>{{ $visit->time }}</td>
                <td>{{ $visit->reason }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
