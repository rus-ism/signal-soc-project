<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Profile;
use App\Models\Region;
use App\Models\School;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportUser implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $school_str = School::where('name', $row[2]);
        dd($school_str);
        return new User([
            //
        ]);
    }
}
