<?php

namespace App\Imports;

use App\Models\Region;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportRegion implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    
    public function model(array $row)
    {
        $cn = Region::where('name',$row[2])->get()->count();
        //dd($cn);
        if ($cn == 0) {
            return new Region([
                'name' => $row[2],        
            ]);
        }
    }


}
