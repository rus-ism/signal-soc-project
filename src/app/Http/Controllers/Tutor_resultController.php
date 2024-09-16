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

class Tutor_resultController extends Controller
{

    public function index(Request $request)  // List all tests
    {
        /*
        $results = Respondent_result::all();
        $r = $results->first();
        $rr = $r->respondent;
        //$quest = $q->question()->get()->count();

        */
        $all_student_count = Scholler_count::all()->sum('count');
        $all_profiles_count = Profile::where('role_id', 0)->get()->count();
        if (($all_profiles_count != 0)AND ($all_student_count != 0))
        {
            $profiles_pircent = ($all_profiles_count * 100) / $all_student_count;
        } else {
            $profiles_pircent = 0;
        }
        $profiles_pircent_rounded = round($profiles_pircent, 2);
        $all_respondents_count = Respondent::all()->count(); 
        $anketed_respondents = DB::table('respondents')->whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('respondent_results')
                  ->whereRaw('respondent_results.respondent_id = respondents.id');
        })
        ->get()->count();
        if (($anketed_respondents != 0) AND ($all_student_count != 0))
        {
            $anketed_pircent = ($anketed_respondents * 100) / $all_student_count;
        } else {
            $anketed_pircent = 0;
        }
        //dd($anketed_pircent);
        $all_results =  Respondent_result::all()->count();



        $quizzes = Quiz::all();
        if ($quizzes->count() > 0)
        {
            $quizzes_array = [];
            $quiz_i = 0;
            foreach ($quizzes as $quiz)
            {
                $quizzes_array[$quiz_i]['quiz'] = $quiz;
                $quiz_result_count = $quiz->respondent_result()->where('updated_at', '>', '2024-09-01')->count();
                $quizzes_array[$quiz_i]['count'] = $quiz_result_count;
                for ($ranges_i = 1; $ranges_i<4; $ranges_i++)
                {                    
                    $sql = 'SELECT COUNT(*) AS cnt FROM respondent_results
                    WHERE respondent_results.quiz_id = ? AND respondent_results.scope >= 
                    (SELECT result_interpretations.from FROM result_interpretations WHERE quiz_id = ? 
                    AND result_interpretations.assessment = ?) AND respondent_results.scope <= 
                    (SELECT result_interpretations.to FROM result_interpretations WHERE quiz_id = ? AND result_interpretations.assessment = ?);';
                    $select_result = DB::select($sql,[$quiz->id,$quiz->id,$ranges_i,$quiz->id,$ranges_i]);
                    $ranges[$ranges_i] = $select_result[0]->cnt;
                    
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
        return view('admin.results', $data);
    }


    public function results_region_detail($region_id, $quiz_id)    //By schools
    {
        $quiz = Quiz::find($quiz_id);
        $region = Region::find($region_id);
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
                $results = $respondent->respondent_result()->where('updated_at', '>', '2024-09-01')->get()->where('quiz_id', $quiz_id);
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
        return view('admin.results-region-detail', $data);
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
