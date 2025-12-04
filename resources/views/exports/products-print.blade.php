<!DOCTYPE html>
<html>
<head>
    <title>Products List</title>
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
    <h1>Products List</h1>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Barcode</th>
                <th>Tool Code</th>
                <th>HSN Code</th>
                <th>Min Rate</th>
                <th>Max Rate</th>
                <th>Min Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->barcode_number ?? '-' }}</td>
                <td>{{ $product->tool_code ?? '-' }}</td>
                <td>{{ $product->hsn_code ?? '-' }}</td>
                <td>{{ $product->minimum_rate ? number_format($product->minimum_rate, 2) : '-' }}</td>
                <td>{{ $product->maximum_rate ? number_format($product->maximum_rate, 2) : '-' }}</td>
                <td>{{ $product->minimum_quantity ?? '-' }}</td>
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