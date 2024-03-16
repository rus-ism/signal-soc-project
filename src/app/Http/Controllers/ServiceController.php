<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Region;
use App\Models\School;
use App\Models\Profile;
use App\Models\Quiz;
use App\Models\Resulst;
use App\Models\Rspondent;
use App\Models\Respondent_result;
use App\Models\Respondent_answer;
use App\Models\Answers;
use App\Models\Question;
use App\Models\Quizacl;
use App\Models\Respondent;
use App\Models\Result_interpretation;
use App\Models\Scholler_count;

class ServiceController extends Controller
{
    public function show_page() {
        return view('admin.service');
    }
}
