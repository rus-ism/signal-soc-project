<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Respondent;

class RespondentController extends Controller
{
    public function createme(Request $request)
    {
        $respondent = new Respondent;
        $respondent->region_id = $request->input('region');
        $respondent->school_id = $request->input('school');
        $respondent->grade = $request->input('grade');
        $respondent->grade = $request->input('litera');
        $respondent->save();
    }
}
