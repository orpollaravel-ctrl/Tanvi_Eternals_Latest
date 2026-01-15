<!DOCTYPE html>
<html>
<head>
    <title>Quotations - Print</title>
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
    <h1>Quotations List</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Salesman</th>
                <th>Contact</th> 
                <th>Metal</th>
                <th>Purity</th>
                <th>Diamond</th>
                <th>Women Ring Size</th>
                <th>Men Ring Size</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quotations as $index => $quotation)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $quotation->customer_name }}</td>
                    <td>{{ $quotation->salesman_name }}</td>
                    <td>{{ $quotation->contact }}</td>
                    <td>{{ ucfirst($quotation->metal) }}</td>
                    <td>{{ $quotation->purity }}</td>
                    <td>{{ $quotation->diamond }}</td>
                    <td>{{ $quotation->women_ring_size_from }} - {{ $quotation->women_ring_size_to }}</td>
                    <td>{{ $quotation->men_ring_size_from }} - {{ $quotation->men_ring_size_to }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
