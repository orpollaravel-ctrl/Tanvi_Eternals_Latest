<!DOCTYPE html>
<html>
<head>
    <title>Expenses - Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .filter-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #555;
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
            font-size: 13px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
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

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">Print</button>

<h1>Expenses List</h1>

@if(request('status'))
    <div class="filter-info">
        Filtered by Status: <strong>{{ ucfirst(request('status')) }}</strong>
    </div>
@endif

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Salesman</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
            <th>Remark</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($expenses as $index => $expense)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $expense->salesman->name ?? '-' }}</td>
                <td>{{ ucfirst($expense->type) }}</td>
                <td>₹{{ number_format($expense->amount, 2) }}</td>
                <td>{{ ucfirst($expense->status) }}</td>
                <td>{{ \Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</td>
                <td>{{ $expense->remark ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align:center;">No expenses found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
