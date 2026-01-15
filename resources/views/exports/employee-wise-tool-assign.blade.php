<table>
    <thead>
        <tr>
            <th>Employee</th>
            <th>Department</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Date</th>
            <th>Rate</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $employee)
            @foreach ($toolAssigns as $assign)
                @foreach ($assign->items as $item)
                    @if($item->emp_id == $employee->id)
                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $assign->department->name ?? '-' }}</td>
                            <td>{{ $item->product->product_name ?? '-' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ \Carbon\Carbon::parse($assign->date)->format('d-m-Y') }}</td>
                            <td>{{ $item->product->purchaseItems->avg('rate') ?? 0 }}</td>
                            <td>
                                {{ $item->quantity * ($item->product->purchaseItems->avg('rate') ?? 0) }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>
