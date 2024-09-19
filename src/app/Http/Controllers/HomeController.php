<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\User;
use App\Models\Region;
use App\Models\School;
use App\Models\Profile;
use App\Models\Quiz;
use App\Models\Quizacl;
use App\Models\Respondent;
use App\Libraries\Quizprocess;
use App\Models\Quiz_school_acl;

class HomeController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        $data = [
            'regions' => $regions,
        ];
        return view('respondet-form', $data);
    }
    public function index_kz()
    {
        session()->put('locale', 'kz');
        $regions = Region::all();
        $data = [
            'regions' => $regions,
        ];
        return view('respondet-form', $data);
    }


    // SHhow availible quizzes
    public function showQuizzes(Request $request)
    {
        $user = User::where('email', $request->input('ic'))->first();
        if (!$user) 
        {
            //dd($request);
            return back()->withInput();
        }
        $respondent = $this->create_respondent($request, $user);
        $school = $respondent->school()->first();
        //dd($respondent->grade);
        $quizzes_acls = Quizacl::where('grade', $respondent->grade)->get();
        //dd($quizzes_acls);
        $quizzes = [];
        if ($quizzes_acls->count() == 0)
        {
            return 'Ooops';
        }
        foreach ($quizzes_acls as $acl) {
            //dd($acl->quiz()->first());
            $quiz = $acl->quiz()->first();
            $cnt = $quiz->quiz_school_acl()->where('school_id', $school->id)->count();
            if ($cnt <> 0)
            {
                $quizzes[] = $acl->quiz()->first();
            }
            
        }
        //dd($quizzes);
        //if (count($quizzes) == 0 ) {
        //    return back()->withInput();
       // }
        $data = [
            'quizzes' => $quizzes,
            'respondent_id' => $respondent->id,
        ];
      //  dd($quizzes_id);
        return view('showquizzes', $data);
    }


    private function create_respondent($request, $user)
    {
        $profile = $user->profile()->first();
        $region_id = $profile->region_id;
        $school_id = $profile->scool_id;
        $grade = $profile->grade;
        $litera = $profile->litera;

        $isset_respondent = $user->respondent()->first();//Respondent::where('user_id')
        //dd($isset_respondent);
        if (!$isset_respondent) {
            $respondent = new Respondent;
            $respondent->region_id = $region_id; #$request->input('region');
            $respondent->school_id = $school_id; #$request->input('school');
            $respondent->grade     = $grade; #$request->input('grade');
            $respondent->litera    = $litera; #$request->input('litera');
            $respondent->user_id   = $user->id;
            $respondent->save();
        } else {
            $respondent = $isset_respondent;
            $respondent->grade = $grade;
            $respondent->update();
        }

        return $respondent;        
    }
// Only BY grade yet
    private function getQuizzes() 
    {
        $acls = Quizacl::where('grade', $this->getGrade())->get();
      //  dd($acls);
        $count = $acls->count();
        if (!$count)
            {
                return 0;
            }
        
        foreach ($acls as $acl)
        {
            $quizzes[] = $acl->quiz;
        }
        return $quizzes;
    }    

    private function getUser() 
    {
        $user = User::find(Auth::user()->id);
        return $user;
    
    }

    private function getProfile() 
    {
        $user = User::find(Auth::user()->id);
        return $user->profile;
    }    

    private function getGrade() 
    {
        $user = User::find(Auth::user()->id);
        $profile = $user->profile;
        return $profile->grade;
    }
}
