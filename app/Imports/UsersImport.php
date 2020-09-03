<?php

namespace App\Imports;

use App\Users2;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Users2([
            'name'     => $row[0],
            'email'    => $row[1], 
            'password' => $row[2],
            ]);
    }
}
