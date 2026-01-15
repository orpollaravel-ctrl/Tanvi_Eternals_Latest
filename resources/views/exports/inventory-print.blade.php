<!DOCTYPE html>
<html>
<head>
    <title>Inventory Calculation</title>
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
            body { margin: 0; }
            h1 { margin-bottom: 20px; }
        }
    </style>
</head>
<body>
    <h1>Inventory Calculation</h1>
    <table>
        <thead>
            <tr>
                <th>Tool Code</th>
                <th>Product Name</th>
                <th>Barcode</th>
                <th>Remaining Qty</th>
                <th>Remaining Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventory as $inv)
            <tr>
                <td>{{ $inv['product']->tool_code ?? '-' }}</td>
                <td>{{ $inv['product']->product_name }}</td>
                <td>{{ $inv['product']->barcode_number ?? '-' }}</td>
                <td>{{ number_format($inv['remaining_qty'], 2) }}</td>
                <td>{{ number_format($inv['remaining_value'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>