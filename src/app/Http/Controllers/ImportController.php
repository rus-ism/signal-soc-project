<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportRegion;
use App\Imports\ImportSchool;
use App\Models\Region;


class ImportController extends Controller
{
    public function importview(Request $request)
    {
        return view('import.region');

    }

    public function schoolview(Request $request)
    {
        return view('import.school');
    }

    public function import(Request $request){
        Excel::import(new ImportRegion, $request->file('file')->store('files'));
        return redirect()->back();
    }    

    public function school(Request $request){
        Excel::import(new ImportSchool, $request->file('file')->store('files'));
        return redirect()->back();
    } 
    
}
