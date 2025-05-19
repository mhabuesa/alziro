<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $data = $row->toArray();

        if (User::where('phone', $data['phone'])->exists()) {
            return;
        }

        $password = rand(10000000, 99999999);

        User::create([
            'name'           => $data['name'],
            'f_name'         => $data['name'],
            'phone'          => $data['phone'],
            'street_address' => $data['street_address'],
            'password'       => bcrypt($password),
            'from_others'    => 1
        ]);
    }
}
