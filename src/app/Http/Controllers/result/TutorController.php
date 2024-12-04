<?php

namespace App\Http\Controllers\result;


use App\QuizAnalyzer\QuizAnalyzer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

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

class TutorController extends Controller
{
    public function quizzes(Request $request)
    {
        $user = $this->auth();        
        $profile = $user->profile()->first();            
        $region = $profile->region()->first();  
        $school =  $profile->school()->first();  
        $QuizAnalyzer = new QuizAnalyzer;

        $all_student_count = Scholler_count::where('school_id', $school->id)->sum('count');
        $all_profiles_count = Profile::where('role_id', 0)->where('scool_id', $school->id)->get()->count();

        if (($all_profiles_count != 0)AND ($all_student_count != 0))
        {
            $profiles_pircent = ($all_profiles_count * 100) / $all_student_count;
        } else {
            $profiles_pircent = 0;
        }
        $profiles_pircent_rounded = round($profiles_pircent, 2);
        $all_respondents_count = Respondent::where('school_id', $school->id)->where('updated_at', '>', '2024-09-01')->count();

        $anketed_respondents = DB::table('respondents')->whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('respondent_results')
                  ->whereRaw('respondent_results.respondent_id = respondents.id')
                  ->whereRaw('respondent_results.updated_at > "2024-09-01"');
        })
        ->where('school_id', $school->id)->get()->count();
        
        if (($anketed_respondents != 0) AND ($all_student_count != 0))
        {
            $anketed_pircent = ($anketed_respondents * 100) / $all_student_count;
        } else {
            $anketed_pircent = 0;
        }

        $sql = 'SELECT COUNT(*) AS cnt FROM respondent_results
        INNER JOIN respondents ON respondent_results.respondent_id = respondents.id
        WHERE respondents.school_id = ?
        AND respondent_results.updated_at > "2024-09-01"';
        $select_result = DB::select($sql,[$school->id]);
        $all_results = $select_result[0]->cnt;

        $quizzes = Quiz::all();
        if ($quizzes->count() > 0)
        {
            $quizzes_array = [];
            $quiz_i = 0;
            foreach ($quizzes as $quiz)
            {
                $quizzes_array[$quiz_i]['quiz'] = $quiz;               
                $quizzes_array[$quiz_i]['count'] = $QuizAnalyzer->getCountByQuizSchool($quiz->id,$school->id);

                for ($ranges_i = 0; $ranges_i<4; $ranges_i++)
                {                                        
                    $ranges[$ranges_i] =  $QuizAnalyzer->getCountByQuizSchoolAssessment($quiz->id,$school->id,$ranges_i);
                    
                }
                if ($quiz->id == 6) {
                    $ranges = array_reverse($ranges);
                    //array_unshift($ranges, 0);
                    
                }
                $quizzes_array[$quiz_i]['ranges'] = $ranges;
                $quiz_i++;                
            }

        }
        $all_ranges = [];
        $all_ranges[0]  = 0;
        $all_ranges[1]  = 0;
        $all_ranges[2]  = 0;
        for ($ranges_i = 0; $ranges_i < 3; $ranges_i++)
        {
            foreach ($quizzes_array as $qa)
            {
                //dd($qa['ranges'][$ranges_i]);
                $all_ranges[$ranges_i] += $qa['ranges'][$ranges_i];
            }
        }          



        $data = [
            'school'  => $school,
            'profile' => $profile,            
            'quizzes' =>  $quizzes,
            'all_student_count' => $all_student_count,
            'all_profiles_count' => $all_profiles_count,
            'profiles_pircent_rounded' => $profiles_pircent_rounded,
            'all_respondents_count' => $all_respondents_count,
            'anketed_respondents' => $anketed_respondents,
            'anketed_pircent' => round($anketed_pircent,2),
            'all_results' => $all_results,
            'quizzes_array' => $quizzes_array,
            'all_ranges' => $all_ranges,


        ];

        return view('tutor.quizzes', $data);        

    }

    //School By Grades
    public function getResultOverviewByQuizSchool($quiz_id)
    {
        $user = $this->auth();        
        $profile = $user->profile()->first();            
        $region = $profile->region()->first();  
        $school =  $profile->school()->first();
        $quiz = Quiz::find($quiz_id);
        $QuizAnalyzer = new QuizAnalyzer;
   
       /*---------- Get School respondents count -------------------------*/
       $select_result = DB::select('SELECT COUNT(*) AS cnt FROM respondents
           INNER JOIN respondent_results ON respondent_results.respondent_id = respondents.id
           WHERE respondents.school_id = ? 
           AND respondent_results.quiz_id = ?
           AND respondent_results.academic_year = "24-25"
           AND respondent_results.updated_at > "2024-09-01";', [$school->id,$quiz_id]);
       $school_respondents_count = $select_result[0]->cnt;        
       /*---------- END Get School respondents count ----------------------*/  
   
       $result_interpretations = Result_interpretation::where('quiz_id', $quiz_id)->get();
       if ($result_interpretations->count()>0) 
       {
           $i = 0;
           foreach ($result_interpretations as $interpret) 
           {
               $ranges[$i]['from'] = $interpret->from;
               $ranges[$i]['to'] = $interpret->to;
               $ranges[$i]['text'] = $interpret->text;
               $i++;
           }
       }  
       
       ///School By Classes
       $grades = [];
       $grade_i = 0;
       for ($class = 5; $class<12; $class++)
       {
           $grades[$grade_i]['grade'] = $class;
   
           //------------- Get respondent Count By Classes ---------------//    
           $grades[$grade_i]['class_respondents_count'] = $QuizAnalyzer->getCountByQuizSchoolGrade($quiz->id,$school->id,$class);;    
           //------------- END Get respondent Count By Classes ------------//    
   
           //------------ Get AVG By Grade ------------------//
           $grades[$grade_i]['avg'] = round($QuizAnalyzer->getAvgByQuizSchoolGrade($quiz->id,$school->id,$class),2);
           //----------- END Get AVG By Grade ---------------//  
           
           //------------- Get respondent Count By Classes and Ranges ---------------//
           $rgi = 0;
           if ((isset($ranges)) AND (count($ranges) > 0))
           {
               foreach($ranges as $range)
               {
                   $grades[$grade_i]['ranges'][$rgi]['range'] = $range;
                   $select_result = DB::select('SELECT COUNT(*) AS cnt FROM respondents
                       INNER JOIN respondent_results ON respondent_results.respondent_id = respondents.id
                       WHERE respondents.school_id = ? AND respondent_results.quiz_id = ?
                       AND respondent_results.updated_at > "2024-09-01"
                       AND respondent_results.scope >= ? AND respondent_results.scope <= ? 
                       AND respondents.grade = ?;', [$school->id,$quiz_id,$range['from'],$range['to'],$class]);
                   //dd($select_result[0]->cnt);
                   $grades[$grade_i]['ranges'][$rgi]['count'] = $QuizAnalyzer->getCountByQuizSchoolGradeRange($quiz->id,$school->id,$class,$range['from'],$range['to']);
                   $rgi++;
                   }               
               };  
           //------------- END Get respondent Count By Classes and Ranges -------------//          
           $grade_i++;
       }
       
       //------ Get AVG By school ----------------//
       $select_result = DB::select('SELECT AVG(respondent_results.scope) AS average FROM respondents
           INNER JOIN respondent_results ON respondent_results.respondent_id = respondents.id
           WHERE respondents.school_id = ? 
           AND respondent_results.quiz_id = ? 
           AND respondent_results.updated_at > "2024-09-01";', [$school->id,$quiz_id]);
       $school_bal_avg = round($select_result[0]->average,2);
       //----------------------------------------//
   
       //------ Get All range assessment count ----------------//
       $by_range_all = [];
       $rgi = 0;
       if ((isset($ranges)) AND (count($ranges) > 0))
       {
           foreach($ranges as $range)
           {
               $by_range_all[$rgi]['range'] = $range;
               $select_result = DB::select('SELECT COUNT(*) AS cnt FROM respondents
                   INNER JOIN respondent_results ON respondent_results.respondent_id = respondents.id
                   WHERE respondents.school_id = ?
                   AND respondent_results.quiz_id = ?
                   AND respondent_results.updated_at > "2024-09-01"
                   AND respondent_results.scope >= ? 
                   AND respondent_results.scope <= ?;', [$school->id,$quiz_id,$range['from'],$range['to']]);
               $by_range_all[$rgi]['count'] = $select_result[0]->cnt;
               $rgi++;
           }
       } 
       //-------------------------------------------------//      
   
       $data = [
           'school' => $school,
           'school_respondents_count' => $school_respondents_count,
           'school_bal_avg' => $school_bal_avg,
           'quiz' => $quiz,
           'grades' => $grades,
           'interprets' => $result_interpretations,
           'by_range_all' => $by_range_all,
       ];    
        return view('tutor.results-school-detail', $data);        
    }





    public function getResultOverviewByGrade($grade, $quiz_id)
    {
        $user = $this->auth();        
        $myprofile = $user->profile()->first();            
        $region = $myprofile->region()->first();  
        $school =  $myprofile->school()->first();
        $school_id = $school->id;
        $quiz = Quiz::find($quiz_id);

        $QuizAnalyzer = new QuizAnalyzer;

        if ((!$quiz)OR(!$school))
        {
            abort(404, 'Ресурс не найден');
        }        
        $all_respondents = $school->respondent()->get()->where('grade', $grade);//->where('updated_at', '>', '2024-09-01');
       // dd($all_respondents);
        $interprets = $quiz->result_interpretation()->get();
   
        
   
        $all_results = [];
        $myResult = [];
        $ri = 0;
        $si = 0;
        foreach ($all_respondents as $respondent)
        {
            $quiz_results = $respondent->respondent_result()->where('quiz_id', $quiz_id)->where('updated_at', '>', '2024-09-01')->latest('updated_at')->limit(1)->get();
            if ($quiz_results->count() > 0) {
                $myResult[$si]['respondent'] = $respondent;
                $profile = $respondent->user()->first()->profile()->first();
                $myResult[$si]['profile'] = $profile;
                $ri = 0;
                foreach ($quiz_results as $result)
                {
                    $myResult[$si]['result'][$ri]['resp_res'] = $result;
   
   
                    $select_result = DB::select('SELECT result_interpretations.assessment AS assessment FROM result_interpretations
                    WHERE result_interpretations.quiz_id = ? 
                    AND result_interpretations.from <= ? 
                    AND result_interpretations.to >= ?;', [$quiz_id,$result->scope,$result->scope]);
                    
                    if ($select_result)                            
                    {
                        $assessment_resp = $select_result[0]->assessment;
                    } else {
                        $assessment_resp = 0;
                    }
   
                    $myResult[$si]['result'][$ri]['assessment'] = $assessment_resp;
                    $ri ++;
                };
                $si ++;
            }
        }
        //------ Get AVG By grade ----------------//
        $select_result = DB::select('SELECT AVG(respondent_results.scope) AS average FROM respondents
            INNER JOIN respondent_results ON respondent_results.respondent_id = respondents.id
            WHERE respondents.school_id = ? 
            AND respondent_results.quiz_id = ? 
            AND respondent_results.academic_year = "24-25"
            AND respondents.grade = ?;', [$school->id,$quiz_id,$grade]);
            $grade_bal_avg = round($select_result[0]->average,2);
        //----------------------------------------//
   
        //------------- Get respondent Count By Classes ---------------//
        $select_result = DB::select('SELECT COUNT(*) AS cnt FROM respondents
            INNER JOIN respondent_results ON respondent_results.respondent_id = respondents.id
            WHERE respondents.school_id = ? 
            AND respondent_results.quiz_id = ? 
            AND respondents.grade = ?;', [$school->id,$quiz_id,$grade]);
        $grade_count = $select_result[0]->cnt;       
        //------------- END Get respondent Count By Classes ------------//         
        
        
        $data = [
            'school' => $school,
            'quiz' => $quiz,
            'grade' => $grade,
            'results'     => $myResult,
            'interprets'  => $interprets, 
            'grade_bal_avg' => $grade_bal_avg,  
            'grade_count' =>$grade_count,         
        ];     
   
        return view('tutor.results-grade-detail', $data);           
    }

    // Check Auth
    private function auth()
    {
        if (!Auth::check()) {
            redirect('login');
        }
        return User::find(Auth::user()->id);        
    }
    


    
}
