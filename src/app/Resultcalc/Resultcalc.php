<?php
namespace App\Resultcalc;

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

class Resultcalc
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

    public function set_result($respondent_result_id)
    {
        $Respondent_result = Respondent_result::find($respondent_result_id);
        $this->set_quiz($Respondent_result->quiz_id);
        $this->set_respondent($Respondent_result->respondent_id);
        $this->scales = Scale::where('quiz_id', $this->quiz->id)->get();
        
        if ($this->scales->count() == 0) {
            $this->scales = null;
            return 2;
        }
        //$answers = [];
        $respondent_answers = Respondent_answer::where('respondent_id',$this->respondent->id)->where('quiz_id', $this->quiz->id)->get();
        $i = 0;
        if ($respondent_answers) {
            foreach ($respondent_answers AS $respondent_answer) {
                $question = Question::find($respondent_answer->question_id);                                
                $answers[$i]['question'] = $question;
                $answers[$i]['answer'] = $respondent_answer;
                $i++;
            }
        } else {
            return 1;
        }
        $this->answers = $answers;        
        $this->result = $Respondent_result;
        return 0;
    }

    public function get_scales() {
        if ($this->scales) {
            return $this->scales;
        } else {
            return null;
        }
    }

    public function calc()
    {
        if (!$this->scales) {
            return 1;
        };
        $scales_array = [];
        $scale_i = 0;
        foreach ($this->scales AS $scale) {            
            $scale_count = 0;
            $scale_scope = 0;
            $keys = $scale->key_scale()->get();
            foreach ($keys AS $key) {
                $quiz_key = $key->quiz_key()->first();
                $responden_answered = Respondent_answer::where('respondent_id', $this->respondent->id)->where('answer_id', $quiz_key->answer_id)->get();
                if ($responden_answered->count() != 0) {
                    $scale_count++;
                }
            }
            $scale_scope = $scale_count * $scale->coefficient;
            $scales_array[$scale_i]['title'] = $scale->title;
            $scales_array[$scale_i]['description'] = $scale->description;
            $scales_array[$scale_i]['max'] = $scale->max;
            $scales_array[$scale_i]['scope'] = $scale_scope;
            $this->scales_array[] = $scales_array[$scale_i];
            $scale_i++;
        }
        return $this->scales_array;            
    }

    public function get_questions()
    {
        if (isset($this->quiz)) {
            $this->questions = Question::where('quiz_id', $this->quiz->id);
        }
        else $this->questions = null;
        return $this->questions;
    }

    public function get_answers()
    {
        if (isset($this->answers)) {
            return $this->answers;
        } else {
            return null;
        }
    }
}
?>