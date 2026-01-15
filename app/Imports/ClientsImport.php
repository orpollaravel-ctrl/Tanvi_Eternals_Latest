<?php

namespace App\Imports;

use App\Models\Client;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ClientsImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    WithChunkReading,
    WithBatchInserts
{
    use SkipsFailures;

    public function model(array $row)
    { 
        return new Client([
            'code'           => $row['code'],
            'name'           => $row['name'],
            'client_type'           => $row['client_type'] ?? null,
            'salesman_name' => $row['salesman_name'] ?? null,
            'mobile_number' => preg_replace('/\D/', '', $row['mobile_number'] ?? null),
            'address_1'      => $row['address_1'] ?? null,
            'address_2'      => $row['address_2'] ?? null,
            'address_3'      => $row['address_3'] ?? null,
            'city'           => $row['city'] ?? null,
            'state'          => $row['state'] ?? null,
            'zip_code'       => $row['zip_code'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.code' => ['required', Rule::unique('clients', 'code')],
            '*.name' => 'required|string|max:255',
            '*.salesman_name' => 'nullable|string|max:255',
            '*.mobile_number' => 'nullable|string|max:20',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }
}
