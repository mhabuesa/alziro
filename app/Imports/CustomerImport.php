<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (User::where('phone', $row['phone'])->exists()) {
            return null; // skip this row
        }

        return new User([
            'name'    => $row['name'],      // Must match Excel header
            'f_name'    => $row['name'],      // Must match Excel header
            'phone'   => $row['phone'],
            'street_address' => $row['street_address'],
            'password' => bcrypt('12345678'),
            'from_others' => 1
        ]);

    }
}
