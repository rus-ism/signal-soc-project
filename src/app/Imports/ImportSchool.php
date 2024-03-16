<?php

namespace App\Imports;

use App\Models\School;
use App\Models\Region;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportSchool implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //dd($row[1]);
        $region_id = Region::where('name', $row[1])->first()->id;
        $cn = School::where('region_id', $region_id)->where('name', $row[4])->get()->count();
        $data = [
            'region_id' => $region_id,
            'ru' => [
                'name'       => $row[4], 
                'type'       => '', 
                'kind'       => '', 
                'locality'   => '',
            ],
            'kz' => [
                'name'       => $row[3], 
                'type'       => '', 
                'kind'       => '', 
                'locality'   => '',
            ],
 
        ];
        if ($cn == 0) {
            return new School($data);
        }
    }
}
