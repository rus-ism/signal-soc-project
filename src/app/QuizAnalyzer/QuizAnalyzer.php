<?php
namespace App\QuizAnalyzer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Region;
use App\Models\School;
use App\Models\Profile;
use App\Models\Quiz;
use App\Models\Resulst;
use App\Models\Respondent;
use App\Models\Respondent_result;
use App\Models\Respondent_answer;
use App\Models\Question;
use App\Models\Scale;

class QuizAnalyzer
{
    public $user;
    public $respondent;
    public $quiz;
    public $result;
    public $answers;
    public $questions;
    public $scales;
    public $scales_array;

    public function set_user($user_id)    
    {
        $this->user = User::find($user_id);
    }
    public function set_quiz($quiz_id)
    {
        $this->quiz = Quiz::find($quiz_id);        
    }
    public function set_respondent($respondent_id)
    {
        $this->respondent = Respondent::find($respondent_id);
    }

    
    public function getCountByQuiz($quiz_id, $Json = false) {

        $sql = 'SELECT COUNT(*) AS cnt
                FROM (
                    SELECT MAX(respondent_results.updated_at) AS latest_update, respondent_results.respondent_id
                    FROM respondent_results
                    INNER JOIN respondents ON respondent_results.respondent_id = respondents.id
                    WHERE respondent_results.quiz_id = ?
                    AND respondent_results.updated_at > "2024-09-01"
                    AND respondent_results.academic_year = "24-25"
                    GROUP BY respondent_results.respondent_id
                ) AS latest_responses';
        $select_result = DB::select($sql,[$quiz_id]);
        $result = $select_result[0]->cnt;          
        if ($Json) {
            return json_encode($result);
        }
        return $result;

    }    
    
    public function getCountByQuizSchool($quiz_id, $school_id, $Json = false) {

        $sql = 'SELECT COUNT(*) AS cnt
                FROM (
                    SELECT MAX(respondent_results.updated_at) AS latest_update, respondent_results.respondent_id
                    FROM respondent_results
                    INNER JOIN respondents ON respondent_results.respondent_id = respondents.id
                    WHERE respondents.school_id = ?
                    AND respondent_results.quiz_id = ?
                    AND respondent_results.updated_at > "2024-09-01"
                    AND respondent_results.academic_year = "24-25"
                    GROUP BY respondent_results.respondent_id
                ) AS latest_responses';
        $select_result = DB::select($sql,[$school_id, $quiz_id]);
        $result = $select_result[0]->cnt;          
        if ($Json) {
            return json_encode($result);
        }
        return $result;

    }




    // Get by Quiz,  reult interpritation
    public function getCountByQuizAssessment($quiz_id, $Assessment, $Json = false) {

        $sql = 'SELECT COUNT(*) AS cnt
                FROM (
                    SELECT rr.*
                    FROM respondent_results rr
                    INNER JOIN respondents r ON r.id = rr.respondent_id
                    INNER JOIN (
                        SELECT respondent_id, MAX(updated_at) AS latest_update
                        FROM respondent_results
                        WHERE quiz_id = ? 
                        AND updated_at > "2024-09-01" 
                        AND academic_year = "24-25"
                        GROUP BY respondent_id
                    ) AS latest_responses ON rr.respondent_id = latest_responses.respondent_id AND rr.updated_at = latest_responses.latest_update
                    WHERE rr.academic_year = "24-25"       
                    AND rr.updated_at > "2024-09-01"            
                    AND rr.scope >= (
                        SELECT ri.from
                        FROM result_interpretations ri
                        WHERE quiz_id = ?
                        AND ri.assessment = ?
                    )
                    AND rr.scope <= (
                        SELECT ri.to
                        FROM result_interpretations ri
                        WHERE quiz_id = ?
                        AND ri.assessment = ?
                    )
                ) AS unique_responses;';
        $select_result = DB::select($sql,[$quiz_id, $quiz_id, $Assessment, $quiz_id, $Assessment]);
       // if($quiz_id == 4) {dd($select_result);};
        $result = $select_result[0]->cnt;          
        if ($Json) {
            return json_encode($result);
        }
        return $result;

    }   

    // Get by Quiz, School, reult interpritation
    public function getCountByQuizSchoolAssessment($quiz_id, $school_id, $Assessment, $Json = false) {

        $sql = 'SELECT COUNT(*) AS cnt
                FROM (
                    SELECT rr.*
                    FROM respondent_results rr
                    INNER JOIN respondents r ON r.id = rr.respondent_id
                    INNER JOIN (
                        SELECT respondent_id, MAX(updated_at) AS latest_update
                        FROM respondent_results
                        WHERE quiz_id = ? 
                        GROUP BY respondent_id
                    ) AS latest_responses ON rr.respondent_id = latest_responses.respondent_id AND rr.updated_at = latest_responses.latest_update
                    WHERE r.school_id = ?                    
                    AND rr.academic_year = "24-25"
                    AND rr.scope >= (
                        SELECT ri.from
                        FROM result_interpretations ri
                        WHERE quiz_id = ?
                        AND ri.assessment = ?
                    )
                    AND rr.scope <= (
                        SELECT ri.to
                        FROM result_interpretations ri
                        WHERE quiz_id = ?
                        AND ri.assessment = ?
                    )
                ) AS unique_responses;';
        $select_result = DB::select($sql,[$quiz_id, $school_id, $quiz_id, $Assessment, $quiz_id, $Assessment]);
       // if($quiz_id == 4) {dd($select_result);};
        $result = $select_result[0]->cnt;          
        if ($Json) {
            return json_encode($result);
        }
        return $result;

    }    

//------------- Get respondent Count By Grade ---------------//
public function getCountByQuizSchoolGrade($quiz_id, $school_id, $grade, $Json = false) {

    $sql = 'SELECT COUNT(*) AS cnt
            FROM (
                SELECT rr.*
                FROM respondent_results rr
                INNER JOIN respondents r ON r.id = rr.respondent_id
                INNER JOIN (
                    SELECT respondent_id, MAX(updated_at) AS latest_update
                    FROM respondent_results
                    WHERE quiz_id = ?
                    GROUP BY respondent_id
                ) AS latest_responses ON rr.respondent_id = latest_responses.respondent_id AND rr.updated_at = latest_responses.latest_update
                WHERE r.school_id = ?
                AND rr.updated_at > "2024-09-01"
                AND rr.academic_year = "24-25"
                AND r.grade = ?
            ) AS unique_responses;';
    $select_result = DB::select($sql,[$quiz_id, $school_id, $grade]);
    $result = $select_result[0]->cnt;          
    if ($Json) {
        return json_encode($result);
    }
    return $result;

}  
    
//------------ Get AVG By Grade ------------------//
public function getAvgByQuizSchoolGrade($quiz_id, $school_id, $grade, $Json = false) {

    $sql = 'SELECT AVG(scope) AS average
            FROM (
                SELECT rr.scope
                FROM respondent_results rr
                INNER JOIN respondents r ON r.id = rr.respondent_id
                INNER JOIN (
                    SELECT respondent_id, MAX(updated_at) AS latest_update
                    FROM respondent_results
                    WHERE quiz_id = ?
                    GROUP BY respondent_id
                ) AS latest_responses ON rr.respondent_id = latest_responses.respondent_id AND rr.updated_at = latest_responses.latest_update
                WHERE r.school_id = ?
                AND r.grade = ?
                AND rr.updated_at > "2024-09-01"
                AND rr.academic_year = "24-25"
            ) AS unique_responses;';
    $select_result = DB::select($sql,[$quiz_id, $school_id, $grade]);
    $result = $select_result[0]->average;          
    if ($Json) {
        return json_encode($result);
    }
    return $result;

}  

 //------------- Get respondent Count By Classes and Ranges ---------------//
 public function getCountByQuizSchoolGradeRange($quiz_id, $school_id, $grade, $from, $to, $Json = false) {

    $sql = 'SELECT COUNT(*) AS cnt
            FROM (
                SELECT rr.*
                FROM respondent_results rr
                INNER JOIN respondents r ON r.id = rr.respondent_id
                INNER JOIN (
                    SELECT respondent_id, MAX(updated_at) AS latest_update
                    FROM respondent_results
                    GROUP BY respondent_id
                ) AS latest_responses ON rr.respondent_id = latest_responses.respondent_id AND rr.updated_at = latest_responses.latest_update
                WHERE r.school_id = ?
                AND rr.quiz_id = ?
                AND rr.updated_at > "2024-09-01"
                AND rr.academic_year = "24-25"
                AND rr.scope >= ?
                AND rr.scope <= ?
                AND r.grade = ?
            ) AS unique_responses;';

    $select_result = DB::select($sql,[$school_id, $quiz_id, $from, $to, $grade]);
    $result = $select_result[0]->cnt;          
    //if ($grade == 6) {dd($result);}
    if ($Json) {
        return json_encode($result);
    }
    return $result;

}  

}
?>