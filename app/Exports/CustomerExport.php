<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomerExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::select('name', 'phone', 'street_address')->get();
    }

    public function headings(): array
    {
        return ['Name', 'Phone', 'Address'];
    }
}
