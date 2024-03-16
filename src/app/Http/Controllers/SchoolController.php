<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\School;

class SchoolController extends Controller
{
    public function get_by_region(Request $request)
    {
        $schools = Region::find($request->input('region_id'))->school()->get(['id', 'name']);
        return json_encode($schools);
    }
}
