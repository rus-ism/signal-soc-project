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
        $quizzes = Quiz::all();
        $data = [
            'quizzes' =>  $quizzes,
        ];        
        return view('admin.service', $data);
    }


    public function delete_dublicates($quiz_id) {
        $quiz = Quiz::find($quiz_id);
        $sql = 'SELECT z.* 
        FROM respondent_results AS w
        JOIN respondent_results AS z ON z.respondent_id = w.respondent_id
                        AND z.quiz_id = w.quiz_id
                        AND z.id > w.id WHERE z.quiz_id = {$quiz_id};';
        $data = [
            'quizzes' =>  $quizzes,
        ];        
        $quizzes = Quiz::all();
        return view('admin.service', $data);
    }

    public function grade_transfer($grade){
        
        $newGrade = $grade+1;
        
        Profile::where('grade', '=', $grade)->update(['grade' => $newGrade]);
        
        return back();
    }
    
    public function graduation(Request $request){
        $profiles = Profile::where('grade', 12)->get();
        foreach ($profiles as $profile) {
            //to long
            $profile->role_id = 6;
            $user = $profile->user()->first();
            $user->role = 6;
            $user->save();                
            
            $profile->save();
        }        
    }


    public function update_respondent_grade(Request $request) {
        if ($request->input('update_grade') != '1') {
            return back();
        }

        $respondents = Respondent::all();
        foreach ($respondents as $respondent) {
            $grade = Profile::where('user_id', $respondent->user_id)->first()->grade;
            $respondent->grade = $grade;
            $respondent->update();
        }
        return back();
        
    }


    public function gitCheck(){
        echo 'ok';
        return back();
    }
}
