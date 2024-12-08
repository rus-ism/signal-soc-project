<?php

namespace App\Http\Controllers\result;

use App\Resultcalc\Resultcalc;
use App\QuizAnalyzer\QuizAnalyzer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
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



class ModeratorController extends Controller
{

    /***************************************
     *  List all tests
     * ***********************************/
    public function getOverviewByQuizzes(Request $request)  
    {

        $user = $this->auth();        
        $profile = $user->profile()->first();  
        if (($profile->role_id == 0) OR ($profile->role_id == 3) )
        {
            abort(403, 'Доступ запрещен');
        }   
        $QuizAnalyzer = new QuizAnalyzer;
        
        $region = $profile->region()->first();  

        $all_respondents = Respondent::where('region_id', $region->id)->where('updated_at', '>', '2024-09-01')->get();
        $all_student_count = Scholler_count::where('region_id', $region->id)->sum('count');
        $all_profiles_count = Profile::where('role_id', 0)->where('region_id',$region->id)->get()->count();
        
        // Pircent of inserted profiles
        if (($all_profiles_count != 0)AND ($all_student_count != 0))
        {
            $profiles_pircent = ($all_profiles_count * 100) / $all_student_count;
        } else {
            $profiles_pircent = 0;
        }
        $profiles_pircent_rounded = round($profiles_pircent, 2);

        $all_respondents_count = Respondent::where('region_id', $region->id)->where('updated_at', '>', '2024-09-01')->get()->count();; 
        $anketed_respondents = DB::table('respondents')->whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('respondent_results')
                  ->whereRaw('respondent_results.respondent_id = respondents.id')
                  ->whereRaw('respondent_results.updated_at > "2024-09-01"');
        })->get()->count();
        if (($anketed_respondents != 0) AND ($all_student_count != 0))
        {
            $anketed_pircent = ($anketed_respondents * 100) / $all_student_count;
        } else {
            $anketed_pircent = 0;
        }

        $sql = 'SELECT COUNT(*) AS cnt FROM respondent_results
        INNER JOIN respondents ON respondent_results.respondent_id = respondents.id
        WHERE respondents.region_id = ?
        AND respondent_results.updated_at > "2024-09-01"';
        $select_result = DB::select($sql,[$region->id]);
        $all_results = $select_result[0]->cnt;

        $quizzes = Quiz::all();
        if ($quizzes->count() > 0)
        {
            $quizzes_array = [];
            $quiz_i = 0;
            foreach ($quizzes as $quiz)
            {
                $quizzes_array[$quiz_i]['quiz'] = $quiz;

                $quizzes_array[$quiz_i]['count'] = $QuizAnalyzer->getCountByQuiz($quiz->id);


                for ($ranges_i = 0; $ranges_i<4; $ranges_i++)
                {                                        
                    $ranges[$ranges_i] =  $QuizAnalyzer->getCountByQuizAssessment($quiz->id,$ranges_i);
                    
                }
                if ($quiz->id == 6) {
                    $ranges = array_reverse($ranges);
                    //array_unshift($ranges, 0);
                    
                }
                $quizzes_array[$quiz_i]['ranges'] = $ranges;
                $result_assisments = Result_interpretation::where('quiz_id', $quiz->id)->get();
                $quizzes_array[$quiz_i]['assesments'] = $result_assisments;
                $quiz_i++;
                
            }

        }
        $all_ranges = [];
        $all_ranges[1]  = 0;
        $all_ranges[2]  = 0;
        $all_ranges[3]  = 0;
        for ($ranges_i = 1; $ranges_i < 4; $ranges_i++)
        {
            foreach ($quizzes_array as $qa)
            {
                //dd($qa['ranges'][$ranges_i]);
                $all_ranges[$ranges_i] += $qa['ranges'][$ranges_i];
            }
        }        
        //dd($all_ranges);

        $data = [
            'quizzes' =>  $quizzes,
            'quizzes_array' => $quizzes_array,
            'all_student_count' => $all_student_count,
            'all_profiles_count' => $all_profiles_count,
            'all_respondents_count' => $all_respondents_count,
            'profiles_pircent_rounded' => $profiles_pircent_rounded,
            'all_results' => $all_results,
            'all_ranges' => $all_ranges,
            'anketed_respondents' => $anketed_respondents,
            'anketed_pircent' => round($anketed_pircent,2),

        ];
        return view('moder.results.results', $data);        
        
    }


    public function getQuizOverviewBySchools($quiz_id)
    {
        $user = $this->auth();        
        $profile = $user->profile()->first();  
        if (($profile->role_id == 0) OR ($profile->role_id == 3) )
        {
            abort(403, 'Доступ запрещен');
        }   
        
        
        $region = $profile->region()->first(); 
        $quiz = Quiz::find($quiz_id);
        if ((!$quiz)OR(!$region))
        {
            abort(404, 'Ресурс не найден');
        }
        
        $schools = $region->school()->get();

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
        $count = 0;
        $sum = 0;
        $byschool = [];

        $si = 0;
        foreach($schools as $school)
        {
            $byschool[$si]['school'] = $school->name;
            $byschool[$si]['school_id'] = $school->id;
            $byschool[$si]['contingent'] =  Scholler_count::where('school_id', $school->id)->get()->sum('count');

            $respondents = $school->respondent()->get();
            $count = 0;//$respondents->count();
            $sum = 0;
            foreach($respondents as $respondent)
            {
                $results = $respondent->respondent_result()->where('quiz_id', $quiz_id)->where('updated_at', '>', '2024-09-01')->where('academic_year', '24-25')->latest('updated_at')->limit(1)->get();
                $count += $results->count();
                $sum = $results->sum('scope');
            }
            $byschool[$si]['count'] =  $count;
            $byschool[$si]['sum'] =  $sum;

            $rgi = 0;
            if ((isset($ranges)) AND (count($ranges) > 0))
            {
                foreach($ranges as $range)
                {
                    $byschool[$si]['ranges'][$rgi]['range'] = $range;
                    $select_result = DB::select('SELECT COUNT(*) AS cnt FROM respondents
                    INNER JOIN respondent_results ON respondent_results.respondent_id = respondents.id
                    WHERE respondents.school_id = ? 
                    AND respondent_results.updated_at > "2024-09-01"
                    AND respondent_results.quiz_id = ? 
                    AND respondent_results.scope >= ? 
                    AND respondent_results.scope <= ?;', [$school->id,$quiz_id,$range['from'],$range['to']]);
                    $byschool[$si]['ranges'][$rgi]['count'] = $select_result[0]->cnt;
                    $rgi++;
                }
            }            

            //------ Get AVG By school ----------------//
            $select_result = DB::select('SELECT AVG(respondent_results.scope) AS average FROM respondents
                INNER JOIN respondent_results ON respondent_results.respondent_id = respondents.id
                WHERE respondents.school_id = ? 
                AND respondent_results.updated_at > "2024-09-01"
                AND respondent_results.academic_year = "24-25"
                AND respondent_results.quiz_id = ? ;', [$school->id,$quiz_id]);
            $byschool[$si]['avg'] = round($select_result[0]->average,2);
            //----------------------------------------//
            $si++;

        }

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
                WHERE respondent_results.quiz_id = ?
                AND respondent_results.updated_at > "2024-09-01"
                AND respondent_results.academic_year = "24-25"
                AND respondent_results.scope >= ? 
                AND respondent_results.scope <= ?;', [$quiz_id,$range['from'],$range['to']]);
                $by_range_all[$rgi]['count'] = $select_result[0]->cnt;
                $rgi++;
            }
        } 
        //-------------------------------------------------//        

        
       //dd($quiz);
        $data = [
            'region' => $region,
            'quiz' => $quiz,
            'schools' => $byschool,
            'interprets' => $result_interpretations,
            'by_range_all' => $by_range_all,
        ];
        return view('moder.results.results-region-detail', $data);
    }

    /**********************************
     * School Results by Grade
     */
    public function getQuizOverviewByGrade($school_id, $quiz_id)
    {
        $user = $this->auth();        
        $profile = $user->profile()->first();  
        if (($profile->role_id == 0) OR ($profile->role_id == 3) )
        {
            abort(403, 'Доступ запрещен');
        }   
        
        
        $region = $profile->region()->first(); 

        $school = School::find($school_id);
        $quiz = Quiz::find($quiz_id);
        if ((!$quiz)OR(!$school))
        {
            abort(404, 'Ресурс не найден');
        }        
        
        if ($school->region()->first()->id != $region->id)
        {
            abort(403, 'Доступ запрещен');
        }


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
        
        /// By Classes
        $grades = [];
        $grade_i = 0;
        for ($class = 5; $class<12; $class++)
        {
            $grades[$grade_i]['grade'] = $class;

            //------------- Get respondent Count By Classes ---------------//
            $grades[$grade_i]['class_respondents_count'] = $QuizAnalyzer->getCountByQuizSchoolGrade($quiz->id,$school->id,$class);

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

            //-------------- GET kontingent By grade and school ----------------------------//
            $grades[$grade_i]['contingent'] = Scholler_count::where('school_id', $school->id)->where('grade', $class)->get()->sum('count');
            //-------------- END GET kontingent By grade and school ----------------------------//            
            

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
        return view('moder.results.results-school-detail', $data);
    }

    /*********************************
     * Grade results by students
     */
    public function getQuizOverviewByStudents($grade, $quiz_id, $school_id) 
    {
        $user = $this->auth();        
        $profile = $user->profile()->first();  
        //dd($profile);
        if (($profile->role_id == 0) OR ($profile->role_id == 3) )
        {
            abort(403, 'Доступ запрещен');
        }   
        
        
        $region = $profile->region()->first(); 

        $school = School::find($school_id);
        $quiz = Quiz::find($quiz_id);
        if ((!$quiz)OR(!$school))
        {
            abort(404, 'Ресурс не найден');
        }        
        
        if ($school->region()->first()->id != $region->id)
        {
            abort(403, 'Доступ запрещен');
        }


       
        $all_respondents = $school->respondent()->get()->where('grade', $grade);
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
            AND respondent_results.updated_at > "2024-09-01"
            AND respondent_results.academic_year = "24-25"
            AND respondents.grade = ?;', [$school->id,$quiz_id,$grade]);
            $grade_bal_avg = round($select_result[0]->average,2);
        //----------------------------------------//

        //------------- Get respondent Count By Classes ---------------//
        $select_result = DB::select('SELECT COUNT(*) AS cnt FROM respondents
            INNER JOIN respondent_results ON respondent_results.respondent_id = respondents.id
            WHERE respondents.school_id = ? 
            AND respondent_results.updated_at > "2024-09-01"
            AND respondent_results.academic_year = "24-25"
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
        return view('moder.results.results-grade-detail', $data);
    }


    /*************************************
     * Answers for result
     */
    public function getResultAnswers($result_id, $quiz_id)
    {
        $result = Respondent_result::find($result_id);

        $respondent = $result->respondent()->first();
        $user = $respondent->user()->first();
        $profile = $user->profile()->first();        

        $my_user = $this->auth();        
        $my_profile = $my_user->profile()->first();  
        if (($my_profile->role_id == 0) OR ($my_profile->role_id == 3) )
        {
            abort(403, 'Доступ запрещен');
        }   
        
        
        $region = $my_profile->region()->first(); 

        if ($profile->region()->first()->id != $region->id)
        {
            abort(403, 'Доступ запрещен');
        }

        //$school = School::find($school_id);
                
        $result_interpretations = Result_interpretation::where('quiz_id', $quiz_id)
        ->where('from','<=', $result->scope)
        ->where('to', '>=', $result->scope)
        ->get();
        
        if ($result_interpretations->count()>0) 
        {
            $interpret = $result_interpretations->first();
            if ($interpret->assessment == 1) {$color_style = 'callout-dange';}
            if ($interpret->assessment == 2) {$color_style = 'callout-warning';}
            if ($interpret->assessment == 1) {$color_style = 'callout-success';}
            
        } else {
            $interpret = FALSE;
            $color_style = 'callout-info';
        }
        


        // /dd($respondent);
        $question_answer = [];
        $quiz = Quiz::find($quiz_id);
        $questions = $quiz->question()->get();
        $i = 0;
        foreach ($questions as $question)
        {
            $question_answer[$i]['question'] = $question->text;
            $respondent_answer = Respondent_answer::where('respondent_result_id', $result->id)->where('question_id', $question->id)->first();
            $respondent_answer_old_answered = Respondent_answer::where('respondent_id', $respondent->id)->where('question_id', $question->id)->first();
            if ((!$respondent_answer) AND (!$respondent_answer_old_answered)){
                $respondent_answer = null;
            } else {
                $respondent_answer = $respondent_answer_old_answered;
            }

            if ($respondent_answer->answer_id != null)
                {
                    $answer_text = $respondent_answer->answer()->first()->text;
                } else {
                    $answer_text = "Нет ответа";
                }
            $scope = $respondent_answer->scope;

            $question_answer[$i]['answer'] = $answer_text;
            $question_answer[$i]['scope'] = $scope;
            $question_answer[$i]['is_answeresd'] = $respondent_answer->answered;
            $question_answer[$i]['date'] = $respondent_answer->updated_at;
            //dd($question_answer[$i]);
            $i++;
        }

        if ($quiz->type_id == 2) {
            $resultcal = New Resultcalc;
            $resultcal->set_user($user->id);
            $resultcal->set_result($result->id);
            $scales = $resultcal->calc();
        } else {
            $scales = null;
        };        

        $data = [
            'user'    => $user,
            'profile' => $profile,
            'quiz'    => $quiz,
            'qas'      => $question_answer,
            'result' => $result,
            'interpret' => $interpret,
            'color_style' => $color_style,
            'scales'  => $scales,
        ];
        return view('moder.results.results-answers', $data);        
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
