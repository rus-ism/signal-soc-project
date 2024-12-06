<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Region;
use App\Models\School;
use App\Models\Profile;
use App\Models\Quiz;
use App\Models\Respondent_result;
use App\Models\Respondent_answer;
use App\Models\Question;
use App\Models\Quizacl;
use App\Models\Respondent;
use App\Models\Quiz_school_acl;
use App\Models\Started_quiz;
use App\Models\Scholler_count;
use App\Models\Result_interpretation;
use Illuminate\Support\Facades\Hash;
use App\Resultcalc\Resultcalc;
use PhpParser\Node\Expr\New_;

class TutorController extends Controller
{

/****
 *
 ************  DASH  *******************************
 *  
 ****/    
    public function dash(Request $request)
    {
        $user = $this->auth();        
        $profile = $user->profile()->first();            
        $region = $profile->region()->first();  
        $school =  $profile->school()->first();  
        $data = [
            'school'  => $school,
            'profile' => $profile,

        ];            
        return view('tutor.dash', $data);
    }

    public function quizes(Request $request)
    {

        $user = $this->auth();    
        //dd($user);
        $profile = $user->profile()->first();            
        //dd($profile);
        $region = $profile->region()->first();  

        $school =  $profile->school()->first();  

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

                $sql = 'SELECT COUNT(*) AS cnt FROM respondent_results
                INNER JOIN respondents ON respondent_results.respondent_id = respondents.id
                WHERE respondents.school_id = ?
                AND respondent_results.quiz_id = ?
                AND respondent_results.academic_year = "24-25"
                AND respondents.updated_at > "2024-09-01"';
                $select_result = DB::select($sql,[$school->id, $quiz->id]);
                //dd($select_result);
                $quiz_result_count = $select_result[0]->cnt;   

                $quizzes_array[$quiz_i]['count'] = $quiz_result_count;

                for ($ranges_i = 0; $ranges_i<3; $ranges_i++)
                {                    
                    $sql = 'SELECT COUNT(*) AS cnt FROM respondent_results
                    INNER JOIN respondents ON respondents.id = respondent_results.respondent_id
                    WHERE respondents.school_id = ?
                    AND respondents.updated_at > "2024-09-01"
                    AND respondent_results.quiz_id = ? 
                    AND respondent_results.scope >= 
                    (SELECT result_interpretations.from FROM result_interpretations 
                    WHERE quiz_id = ? 
                    AND result_interpretations.assessment = ?) 
                    AND respondent_results.scope <= 
                    (SELECT result_interpretations.to FROM result_interpretations 
                    WHERE quiz_id = ? 
                    AND result_interpretations.assessment = ?);';
                    $select_result = DB::select($sql,[$school->id,$quiz->id,$quiz->id,$ranges_i,$quiz->id,$ranges_i]);
                    $ranges[$ranges_i] = $select_result[0]->cnt;
                    
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


/****
 *
 ************  By School  *******************************
 *  
 ****/  

 public function results_school_detail($quiz_id)    
 {
     $user = $this->auth();        
     $profile = $user->profile()->first();            
     $region = $profile->region()->first();  
     $school =  $profile->school()->first();
     $quiz = Quiz::find($quiz_id);

    /*---------- Get School respondents count -------------------------*/
    $select_result = DB::select('SELECT COUNT(*) AS cnt FROM respondents
        INNER JOIN respondent_results ON respondent_results.respondent_id = respondents.id
        WHERE respondents.school_id = ? 
        AND respondent_results.quiz_id = ?
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
        $select_result = DB::select('SELECT COUNT(*) AS cnt FROM respondents
        INNER JOIN respondent_results ON respondent_results.respondent_id = respondents.id
        WHERE respondents.school_id = ? 
        AND respondent_results.quiz_id = ? 
        AND respondent_results.updated_at > "2024-09-01"
        AND respondents.grade = ?;', [$school->id,$quiz_id,$class]);
        $class_respondents_count = $select_result[0]->cnt;      
        $grades[$grade_i]['class_respondents_count'] = $class_respondents_count;    
        //------------- END Get respondent Count By Classes ------------//    

        //------------ Get AVG By Grade ------------------//
        $select_result = DB::select('SELECT AVG(respondent_results.scope) AS average FROM respondents
            INNER JOIN respondent_results ON respondent_results.respondent_id = respondents.id
            WHERE respondents.school_id = ? 
            AND respondents.grade = ?
            AND respondent_results.updated_at > "2024-09-01"
            AND respondent_results.quiz_id = ? ;', [$school->id,$class,$quiz_id]);
        $grades[$grade_i]['avg'] = round($select_result[0]->average,2);
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
                $grades[$grade_i]['ranges'][$rgi]['count'] = $select_result[0]->cnt;
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


/****
 *
 ************  By Grade  *******************************
 *  
 ****/  

 public function results_grade_detail($grade, $quiz_id)
 {
     $user = $this->auth();        
     $myprofile = $user->profile()->first();            
     $region = $myprofile->region()->first();  
     $school =  $myprofile->school()->first();
     $school_id = $school->id;
     $quiz = Quiz::find($quiz_id);
     if ((!$quiz)OR(!$school))
     {
         abort(404, 'Ресурс не найден');
     }        
     $all_respondents = $school->respondent()->get()->where('grade', $grade)->where('updated_at', '>', '2024-09-01');
    
     $interprets = $quiz->result_interpretation()->get();

     

     $all_results = [];
     $myResult = [];
     $ri = 0;
     $si = 0;
     foreach ($all_respondents as $respondent)
     {
         $quiz_results = $respondent->respondent_result()->where('quiz_id', $quiz_id)->where('updated_at', '>', '2024-09-01')->get();
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


/****
 *
 ************  Schoolars  *******************************
 *  
 ****/  

    public function schoolars(Request $request)
    {
        $user = $this->auth();        
        $profile = $user->profile()->first();            
        $region = $profile->region()->first();  
        $school =  $profile->school()->first(); 
        //dd($school)       ;
        $ress = $school->profile()->where('role_id', 0)->get()->sortBy(['grade','desc']);
        if ($ress->count() > 0)
        { 
            $i = 0;
            foreach ($ress as $res)
            {
                $schoolars[$i]['profile'] = $res;
                $results = [];
                $resp_user_id = $res->user_id;
                $respondent = Respondent::where('user_id', $resp_user_id)->first();

                if ($respondent) 
                {	
                        if ($respondent->count() > 0)
                        {
                            
                            $respondent_results = $respondent->respondent_result()->where('updated_at', '>', '2024-09-01')->get()->unique('quiz_id');
                            if ($respondent_results) 
                            {

                                    if ($respondent_results->count() > 0) 
                                    {
                                        foreach ($respondent_results as $result) 
                                        {
                                            // dd($result->quiz()->first());
                                            $results[] = $result;
                                        }                        
                                    }
                            }
                        }  
                }
                $schoolars[$i]['results'] = $results;
                $i++;
            }
        } else {
            $schoolars = [];
        }

        //$schoolars = $res;

        $data = [
            'school'  => $school,
            'profile' => $profile,            
            'schoolars'   => $schoolars,
        ];
        return view('tutor.scoolars', $data);
        

    }

/***
 * 
 * ********* Schoolar Count check
 * 
 ******/
    public function schoolar_check_count(Request $request)
    {
        $error = 0;
        $user = $this->auth();        
        $profile = $user->profile()->first();            
        $region = $profile->region()->first();  
        $school =  $profile->school()->first();

        //$current_count = $school->profile()->where('grade', $request->input('grade'));
        $current_count = Profile::where('scool_id', $school->id)->where('grade',$request->input('grade'))->count();


        $school = School::find($request->input('school_id'));
        $schoolar_count = $school->scholler_count()->where('grade', $request->input('grade'))->first();
        if (!$schoolar_count) {
            $error = 2;
            $data = [
                'error'   => $error,
                'comment' => 'Moderator not filled data',
            ];
            return $data;
        };

        if($current_count >= $schoolar_count->count) {
            $error = 1;            
        } 

        $data = [
            'error' => $error,
            'count' => $schoolar_count->count,
            'current_count' => $current_count,
        ];
        return $data;
    }




/****
 *
 ************  Schoolar link  *******************************
 *  
 ****/  
public function schoolar_link(Request $request)
{
    $user = $this->auth();        
    $profile = $user->profile()->first();            
    $region = $profile->region()->first();  
    $school =  $profile->school()->first();

    $action = $request->input('action');

   // dd($action);
   if ($action == 'show_link_form')
   {
    $unlinked_profiles = Profile::whereNull('region_id')->get();
    $p=$unlinked_profiles[0];
    //dd($p->user()->first()->email);
    $data = [
        'profiles' => $unlinked_profiles,
    ];
    


    return view('tutor.scoolar-link-form', $data);
   } 
   if ($action == 'link')
   {
       $school_id = $school->id;
       $region_id = $region->id;
       $user_id = $name = $request->input('user_id');

       
       $linkuser = User::find($user_id);
       $link_profile = Profile::where('user_id', $user_id)->first();
       $link_profile->region_id = $region_id;
       $link_profile->scool_id = $school_id;
       $link_profile->save();

       $respondent = Respondent::where('user_id', $user_id)->first();
       if ($respondent) {
            $respondent->school_id = $school_id;
            $respondent->region_id = $region_id;
            $respondent->save();
       };

       return redirect()->action([TutorController::class, 'schoolars']);
   }              


}



/****
 *
 ************  Schoolar Add  *******************************
 *  
 ****/  

    public function schoolar_add(Request $request)
    {
        $user = $this->auth();        
        $profile = $user->profile()->first();            
        $region = $profile->region()->first();  
        $school =  $profile->school()->first();

        $action = $request->input('action');

       // dd($action);
       if ($action == 'show_form')
       {
        $ik_str = $region->id.''.$school->id.''.random_int(1000, 99999);

        $data = [
            'ic' => $ik_str,
            'region_id' => $region->id,
            'school_id' => $school->id,
            'school'  => $school,
        ];
        return view('tutor.scoolar-add-form', $data);
       } 
       if ($action == 'add')
       {

           $name = $request->input('name');
           $email = $request->input('email');
           $grade = $request->input('grade');
           $litera = $request->input('litera');
           $password = '123123123';
           $school_id = $school->id;
           $region_id = $region->id;

           
           $user = User::create([
               'name' => $name,
               'email' => $email,
               'role' => 0,
               'password' => Hash::make($password),                
           ]);
   
           $profile = Profile::create([
               'user_id'           =>      $user->id,
               'role_id'           =>      0,
               'region_id'         =>      $region->id,
               'scool_name'        =>      $school->name,
               'scool_id'          =>      $school_id,
               'grade'             =>      $grade,
               'litera'            =>      $litera,
               'fio'               =>      $name,            
               ]);


           return redirect()->action([TutorController::class, 'schoolars']);
       }              


    }


/****
 *
 ************  Schoolar EDIT  *******************************
 *  
 ****/  

 public function schoolar_edit(Request $request)
 {
     $user = $this->auth();        
     $profile = $user->profile()->first();            
     $region = $profile->region()->first();  
     $school =  $profile->school()->first();

     $action = $request->input('action');

    // dd($action);

    if ($action == 'edit')
    {
        $user_id = $request->input('user_id');
        $fio = $request->input('fio');
        $grade = $request->input('grade');
        $litera = $request->input('litera');
        $password = '123123123';
        $school_id = $school->id;
        $region_id = $region->id;


        $user = User::findOrFail($user_id);
        $user->name = $fio;
        $user->update();

        
        $profile = $user->profile()->first();
        $profile->fio = $fio;
        $profile->grade = $grade;
        $profile->litera = $litera;
        $profile->update();

        $data = [
            'user_id' => $user_id,
        ];        
        return json_encode($data);
    }              


 }    

/****
 *
 ************  Schoolar Delete  *******************************
 *  
 ****/  

 public function schoolar_delete(Request $request)
 {
     $user = $this->auth();        
     $profile = $user->profile()->first();            

     $action = $request->input('action');

    // dd($action);

    if ($action == 'delete')
    {
        $user_id = $request->input('user_id');

        $user = User::findOrFail($user_id);        
        $profile = $user->profile()->first();

        $respondent =  $user->respondent()->first();
        if ($respondent) {
            $result_count = $respondent->respondent_result()->count();
            } else {// Respondent count == 0
                $result_count = 0;

            }
        if ($result_count != 0) {
            $error = 1;
        } else {
            $user->delete();
            $error = 0;
        }

        $data = [
            'error' => $error,
            'user_id' => $user_id,
        ];        
        return json_encode($data);
    }              


 }    




 /****
 *
 ************  Schoolar Unlink  *******************************
 *  
 ****/  

 public function schoolar_unlink(Request $request)
 {
     $user = $this->auth();        
     $profile = $user->profile()->first();            

     $action = $request->input('action');

    // dd($action);

    if ($action == 'unlink')
    {
        $user_id = $request->input('user_id');

        $user = User::findOrFail($user_id);        
        $profile = $user->profile()->first();
        $profile->region_id = null;
        $profile->scool_id = null;
        $profile->save();
        $respondent =  $user->respondent()->first();
        if ($respondent) {
            $respondent->region_id = null;
            $respondent->school_id = null;
            $respondent->save();

        }
        $error = 0;
        $data = [
            'error' => $error,
            'user_id' => $user_id,
        ];        
        return json_encode($data);
    }              


 }    



/********* Revert To 5 Grade******************
 * 
 */
public function schoolar_revert(Request $request)
{
    $user = $this->auth();        
    $profile = $user->profile()->first(); 
    $to = $request->input('tograde');

    $action = $request->input('action');


   if ($action == 'revert')
   {
       $profile_id = $request->input('profile_id');
       
//dd($user_id);
       $profile = Profile::findOrFail($profile_id);
       $st_user = $profile->user()->first();
       //$user = User::findOrFail($user_id);
       /*
       if (($profile->grade != '6')AND($to == 5)) {
        $respondent =  $user->respondent()->first();
        $error = 2;
        $data = [
            'error' => $error,
            'comment' => 'Ученик не в 6 классе',
        ];        
        return json_encode($data);
       }

*/


       $profile->grade = $to;
       $profile->save();
       $respondent =  $st_user->respondent()->first();
       if ($respondent) {
           $respondent->grade = $to;
           $respondent->save();

       }
       $error = 0;
       $data = [
           'error' => $error,
           'user_id' => $st_user->id,
       ];        
       return json_encode($data);
   }              


} 


/****
 *
 ************  By Student  *******************************
 *  
 ****/  

    public function results_answers($result_id, $quiz_id)
    {
        $my_user = $this->auth();        
        $my_profile = $my_user->profile()->first();            
        $region = $my_profile->region()->first();  
        $school =  $my_profile->school()->first();



        $result = Respondent_result::find($result_id);
        if (!$result) {
            return view('error.403');
        }         
        $respondent = $result->respondent()->first();
        if (!$respondent) {
            return view('error.403');
        }        
        $user = $respondent->user()->first();
        $profile = $user->profile()->first();

        if ($my_profile->scool_id != $profile->scool_id)
        {
            return view('error.403');
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
            if (!$respondent_answer) {
                //return view('error.403');
                $answer_text = "Нет ответа";
                $question_answer[$i]['answer'] = $answer_text;
                $question_answer[$i]['scope'] = 0;
                $question_answer[$i]['is_answeresd'] = 0;
                $question_answer[$i]['date'] = 0;
            }  else { 
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
                   }
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
            'qas'     => $question_answer,
            'scales'  => $scales,
        ];
        return view('tutor.results-answers', $data);
        dd($questions);
        dd($result);
    }    





/****
 *
 ************  By Student Debug  *******************************
 *  
 ****/  

 public function results_answers_debug($result_id, $quiz_id)
 {
     $my_user = $this->auth();        
     $my_profile = $my_user->profile()->first();            
     $region = $my_profile->region()->first();  
     $school =  $my_profile->school()->first();



     $result = Respondent_result::find($result_id);
     if (!$result) {
         return view('error.403');
     }         
     $respondent = $result->respondent()->first();
     if (!$respondent) {
         return view('error.403');
     }        
     $user = $respondent->user()->first();
     $profile = $user->profile()->first();

     if ($my_profile->scool_id != $profile->scool_id)
     {
         return view('error.403');
     }
     // /dd($respondent);
     $question_answer = [];
     $quiz = Quiz::find($quiz_id);
     $questions = $quiz->question()->get();
     //dd($questions);
     $i = 0;
     foreach ($questions as $question)
     {
         $question_answer[$i]['question'] = $question->text;
         $respondent_answer = Respondent_answer::where('respondent_id', $respondent->id)->where('question_id', $question->id)->first();

         if (!$respondent_answer) {
             //return view('error.403');
             $answer_text = "Нет ответа";
             $question_answer[$i]['answer'] = $answer_text;
             $question_answer[$i]['scope'] = 0;
             $question_answer[$i]['is_answeresd'] = 0;
             $question_answer[$i]['date'] = 0;
         }  else { 
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
                }
         $i++;
     }
   //  dd($question_answer);
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
         'qas'     => $question_answer,
         'scales'  => $scales,
     ];
     return view('tutor.results-answers', $data);
     dd($questions);
     dd($result);
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
