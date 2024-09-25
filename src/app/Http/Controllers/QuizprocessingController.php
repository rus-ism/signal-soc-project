<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Resultcalc\Resultcalc;

use App\Models\User;
use App\Models\Profile;
use App\Models\Quiz;
use App\Models\Resulst;
use App\Models\Question;
use App\Models\Question_list;
use App\Models\Quizacl;
use App\Models\Quiz_school_acl;
use App\Models\Quizprocessing;
use App\Models\Answer;
use App\Models\Respondent;
use App\Models\Respondent_answers;
use App\Models\Respondent_result;
use App\Models\User_answer;
use LDAP\Result;
//use App\Models\Quiz_school_acl;
use App\Models\Respondent_answer;
use App\Models\Started_quiz;
use App\Models\Quiz_key;

class QuizprocessingController extends Controller
{

    public $counter;
    public $quizprocessing;
    public $result;

/// Check Access to this quiz for this respondent
    public function check_quiz_acl($quiz, $respondent)
    {

        $grade = $respondent->grade;
        $user = $respondent->user();
        //dd($user);

        $ok = true;
        $school = $respondent->school()->first();
        $school_acl = Quiz_school_acl::where('quiz_id', $quiz->id)->where('school_id', $school->id)->first();
        if (!$school_acl) {
            $ok = false;
        }
        $grade_acl = Quizacl::where('quiz_id', $quiz->id)->where('grade', $grade)->first();
        if(!$grade_acl) {
            $ok = false;
        }
        return $ok;

    }


    public function publictest(Request $request)
    {
        $quiz_id = $request->input('quiz_id');
        $respondent_id = $request->input('respondent_id');
        $quiz = Quiz::find($quiz_id);
        $respondent = Respondent::find($respondent_id);
        if(!$respondent) {
            abort(403, 'Доступ запрещен');
        }
        //dd($respondent);
        if(!$this->check_quiz_acl($quiz, $respondent)) {
            abort(403, 'Доступ запрещен');
        }
        if (!$respondent)
        {
            return 'error';
        }
        $questions = Question::where('quiz_id', $quiz_id)->get();
        //dd($questions[0]->answer[0]->text);
        $school = $respondent->school()->first();
        $started_quiz = new Started_quiz;
        $started_quiz->quiz_id = $quiz_id;
        $started_quiz->respondent_id = $respondent->id;
        $started_quiz->school_id = $school->id;
        $started_quiz->save();

        $data = [
            'quiz'            => $quiz,
            'respondent'      => $respondent,
            'questions'       => $questions,
            'quiz_id'         => $quiz_id,
            'started_quiz_id' => $started_quiz->id,
        ];


        return view('tpl', $data);
    }

    public function simplefinish(Request $request)
    {
        $started_quiz = Started_quiz::find($request->input('started_quiz_id'));
        if ($started_quiz)
        {
            $started_quiz->delete();
        }        
        $quiz_id = $request->input('quiz_id');
        $quiz = Quiz::find($quiz_id);
        $respondent_id = json_decode($request->input('respondent'))->id;
        $respondent = Respondent::find($respondent_id);
        $user = $respondent->user();

        $school = $respondent->school()->first();

        /*******************************
         * For Quizzes with key without scales *
        ********************************/
        if ($quiz->type_id == 3) {
            ;
        }


        $scope = 0;
        //dd($respondent_id);
        foreach($request->input('ans') as $key => $value )
        {
          $answered = 1;
          
          if ($value == 0) {
            $answered = 0;
            $ans_scope = null;
            $value = null;
          } else {
                if ($quiz->type_id != 3) {
                    $ans_scope = Answer::find($value)->scope;
                } elseif ($quiz->type_id == 3) {
                    $quiz_key = Quiz_key::where('quiz_id',$quiz->id)->where('question_id', $key)->where('answer_id',$value)->first();
                    if ($quiz_key) {
                        $ans_scope = $quiz_key->scope;
                    } else {
                        $ans_scope = 0;
                    }
                }
          }

          $isset_respondent_answer = Respondent_answer::where('respondent_id', $respondent_id)->where('quiz_id', $quiz_id)->where('question_id', $key)->first();
          //dd($isset_respondent_answer);
          if (!$isset_respondent_answer) {
            $respondent_answer = Respondent_answer::create([
                'respondent_id' => $respondent_id,
                'quiz_id'       => $quiz_id,
                'question_id'   => $key,
                'answer_id'     => $value,
                'answered'      => $answered,
                'scope'         => $ans_scope,
                'session'       => Session::getId(),
            ]);
           /* 
            $respondent_answer = new Respondent_answer;
            $respondent_answer->respondent_id = $respondent_id;
            $respondent_answer->quiz_id = $quiz_id;
            $respondent_answer->question_id = $key;
            $respondent_answer->answer_id = $value;
            $respondent_answer->answered = $answered;
            $respondent_answer->scope = $ans_scope;
            $respondent_answer->session = Session::getId();
            $respondent_answer->save();
            */
          } else {
            $isset_respondent_answer->answer_id = $value;
            $isset_respondent_answer->answered = $answered;
            $isset_respondent_answer->scope = $ans_scope;
            $isset_respondent_answer->updated_at = now();
            $isset_respondent_answer->session = Session::getId();
            $isset_respondent_answer->save();            
          }


        }
        $sum = Respondent_answers::where('respondent_id', $respondent_id)->where('quiz_id', $quiz_id)->where('session', Session::getId())->sum('scope');
 
        $isset_respondent_result = respondent_result::where('respondent_id', $respondent_id)->where('quiz_id', $quiz_id)->first();

        if (!$isset_respondent_result)
        {
            $result = Respondent_result::create([
                'respondent_id' => $respondent_id,
                'quiz_id'       => $quiz_id,
                'count'         => count($request->input('ans')),
                'scope'         => $sum,
                'session'       => Session::getId(),                
            ]);
         /*   
            $result = new Respondent_result;
            $result->respondent_id = $respondent_id;
            $result->quiz_id = $quiz_id;
            $result->count = count($request->input('ans'));
            $result->scope = $sum;
            $result->session = Session::getId();
            $result->save();
        */
        } else {
            $isset_respondent_result->count = count($request->input('ans'));
            $isset_respondent_result->scope = $sum;
            $isset_respondent_result->updated_at = now();
            $isset_respondent_result->session = Session::getId();
            $isset_respondent_result->save();            
        }


        
        // Prepare Quizzes list
        $quizzes_acls = Quizacl::where('grade', $respondent->grade)->get();
        //dd($quizzes_acls);
        $quizzes = [];
        foreach ($quizzes_acls as $acl) {
            //dd($acl->quiz()->first());
            $cquiz = $acl->quiz()->first();
            $resp_quiz = Respondent_result::where('respondent_id', $respondent_id)->where('quiz_id',  $cquiz->id)->get()->count();
            $cnt = $cquiz->quiz_school_acl()->where('school_id', $school->id)->count();
          //  dd($resp_quiz);
            if (($resp_quiz == 0) && ($cnt <> 0)) {  
                $quizzes[] = $acl->quiz()->first();                 
            }
        }
       // dd($quizzes);
        $data = [
            'quizzes' => $quizzes,
            'respondent_id' => $respondent->id,
        ];
      //  dd($quizzes_id);
        return view('showquizzes', $data);

    }



    public function start(Request $request)
    {
        $quiz_id = $request->input('quiz_id');
        $quiz = Quiz::find($quiz_id);
        if ($quiz->question->count() == 0 ) {
            return redirect('/');
        }
        $user = $this->getUser();
        $profile = $user->profile()->first();
        $acl = $this->check_acls($user->id, $quiz);
 
        if (!$this->processing_check($quiz->id, $user->id))
            {
                //return redirect('/');

        /* Creating result row */
                $result_data = [
                    'user_id' => $user->id,
                    'quiz_id' => $quiz->id,
                ];
                
                $result = Resulst::Create($result_data);
                $this->result = $result;
        // Creating processing row        
                $quizprocessing_data = [
                    'quiz_id'    => $quiz->id,
                    'user_id'    => $user->id,
                    'results_id' => $result->id,
                    'current'    => 1,
                ];

                $quizprocessing = Quizprocessing::Create($quizprocessing_data);
                $this->quizprocessing = $quizprocessing;

            /*
            // This need to create method with generates question list by some rule
            */
                if ($quiz->type_id == 1)
                    {
                        $question_lists = $this->question_list_generate($result->id, $quiz->id);
                    }; 

            } // END crating
        
        $quizprocessing = Quizprocessing::where('quiz_id', $quiz_id)->where('user_id', $user->id)->first();
        $this->quizprocessing = $quizprocessing;
        $result = Resulst::find($quizprocessing->results_id);
        $this->result = $result;
        $question_lists = $this->result->question_list;
        
        

        $cur_question = $this->get_current_question();
        $cur_question_list = Question_list::where('results_id', $this->result->id)->where('counter', $this->quizprocessing->current)->first();

        $answers = $this->get_answers($cur_question->id);
        $this->counter = 1;
        $data = [
            'question_lists'    =>  $question_lists,
            'question_list'     =>  $cur_question_list,
            'question'          =>  $cur_question,
            'question_list_id'  =>  $cur_question->id,
            'answers'           =>  $answers,
            'profile'           =>  $profile,
            'current'           =>  $quizprocessing->current,
            'result'            =>  $result,
            'result_id'         =>  $result->id,
            'quizprocessing_id' =>  $quizprocessing->id,
        ];

        return view('quiz-processing', $data);
    
    }


    public function noaction(Request $request)
    {
/*        
        $this->result = Resulst::find($request->input('result_id'));
        $this->quizprocessing = Quizprocessing::find($request->input('quizprocessing_id'));        
        if ($request->input('ans') != '0')
        {
            $user_answer = User_answer::create([
                'result_id'        => $request->input('result_id'),
                'question_list_id' => $request->input('question_list_id'),
                'answer_id'        => $request->input('ans'),
            ]);
            
            $current_question_list = $this->result->question_list->where('counter', $this->quizprocessing->current)->first();
            $current_question_list->answered = 1;
            $current_question_list->save();
        }

        if ($this->check_current_over() > $this->quizprocessing->current)
            {
                $this->quizprocessing->current++;  
            } 
        $this->quizprocessing->save();
        $current_question_list = $this->result->question_list->where('counter', $this->quizprocessing->current)->first();
        $question_lists = $this->result->question_list;

       $curent_question = $this->get_current_question();
       
       $answers = $this->get_answers($curent_question->id);
        $data = [
            'quizprocessing'        => $this->quizprocessing,
            'question_lists'        => $question_lists,
            'question'              => $curent_question,
            'answers'               => $answers,
            'result'                => $this->result,
            'current_question_list' => $current_question_list,
           // 'its_last'              => $its_last,
        ];
  
        return json_encode($data);
*/
    }
// END NoAction






    public function next(Request $request)
    {
        
        $this->result = Resulst::find($request->input('result_id'));
        $this->quizprocessing = Quizprocessing::find($request->input('quizprocessing_id'));    
        $user_answered = User_answer::where('question_list_id', $request->input('question_list_id'))->get();
        //$user_answered = $this->check_answer($request->input('result_id'), $request->input('question_list_id'), $request->input('ans') );   

        if ($request->input('ans') != '0')
        {
            if ( $user_answered->count() == 0 ) {

                $user_answer = User_answer::create([
                    'result_id'        => $request->input('result_id'),
                    'question_list_id' => $request->input('question_list_id'),
                    'answer_id'        => $request->input('ans'),
                ]);
                
                $current_question_list = $this->result->question_list->where('counter', $this->quizprocessing->current)->first();
                $current_question_list->answered = 1;
                $current_question_list->save();
            } else {
                $user_ans = $user_answered->first();
                $user_ans->answer_id = $request->input('ans');
                $user_ans->save();
            }
        }

        if ($this->check_current_over() > $this->quizprocessing->current)
            {
                $this->quizprocessing->current++;  
            } 
       // return $this->quizprocessing;
        $this->quizprocessing->save();

        $current_question_list = $this->result->question_list->where('counter', $this->quizprocessing->current)->first();
        $question_lists = $this->result->question_list;

       $curent_question = $this->get_current_question();
       
       $answers = $this->get_answers($curent_question->id);
       $user_answered = User_answer::where('question_list_id', $current_question_list->id)->get();       
       if ($user_answered->count() != 0 ) {
        $user_ans = $user_answered->first();
       } else {
            $user_ans = 0;
       }
        $data = [
            'quizprocessing'        => $this->quizprocessing,
            'question_lists'        => $question_lists,
            'question'              => $curent_question,
            'answers'               => $answers,
            //'user_answered'         => $user_answered->first()->id,
            'result'                => $this->result,
            'current_question_list' => $current_question_list,
            'user_answered'         => $user_ans,
           // 'its_last'              => $its_last,
        ];
  
        return json_encode($data);

    }
// END NEXT


public function back(Request $request)
{
    
    $this->result = Resulst::find($request->input('result_id'));
    $this->quizprocessing = Quizprocessing::find($request->input('quizprocessing_id'));  
    $user_answered = User_answer::where('question_list_id', $request->input('question_list_id'))->get();          
    if ($request->input('ans') != '0')
    {
        if ( $user_answered->count() == 0 ) {

            $user_answer = User_answer::create([
                'result_id'        => $request->input('result_id'),
                'question_list_id' => $request->input('question_list_id'),
                'answer_id'        => $request->input('ans'),
            ]);
            
            $current_question_list = $this->result->question_list->where('counter', $this->quizprocessing->current)->first();
            $current_question_list->answered = 1;
            $current_question_list->save();
        } else {
            $user_ans = $user_answered->first();
            $user_ans->answer_id = $request->input('ans');
            $user_ans->save();
        }
    }

    if ($this->quizprocessing->current > 1)
        {
            $this->quizprocessing->current--;  
        } 
    $this->quizprocessing->save();
    $current_question_list = $this->result->question_list->where('counter', $this->quizprocessing->current)->first();
    $question_lists = $this->result->question_list;

   $curent_question = $this->get_current_question();
   
   $answers = $this->get_answers($curent_question->id);
   $user_answered = User_answer::where('question_list_id', $current_question_list->id)->get();       
   if ($user_answered->count() != 0 ) {
    $user_ans = $user_answered->first();
   } else {
        $user_ans = 0;
   }
    $data = [
        'quizprocessing'        => $this->quizprocessing,
        'question_lists'        => $question_lists,
        'question'              => $curent_question,
        'answers'               => $answers,
        'result'                => $this->result,
        'current_question_list' => $current_question_list,
        'user_answered'         => $user_ans,
       // 'its_last'              => $its_last,
    ];

    return json_encode($data);

}
// END BACK


    public function finish(Request $request)
    {
        $this->result = Resulst::find($request->input('m_result_id'));

        //$this->result->end_at = \Carbon\Carbon::now();
        $user_answers = $this->result->user_answer;
        $scope = 0;
        foreach ($user_answers as $user_ans)
        {
            $answer = Answer::find($user_ans->answer_id);
            $scope += $answer->scope;
        }
        $this->result->scope = $scope;       
        $this->result->answered_count = $user_answers->count();
        $this->result->save();
        if ($this->quizprocessing = Quizprocessing::find($request->input('quizprocessing_id')))
        {
            $this->quizprocessing->delete();
        }
        return 'Тест окончен';
        
      //  $this->result->save(); 
       // dd($this->result);
    }    



    public function check_answer($result_id, $question_list_id, $answer_id)
    {
        //$question_list = Question_list::find($question_list_id);
        //$question = Question::find($question_list->question_id);
        //if ($question->type == 1) {
            $user_answer = user_answer::where('question_list_id', $question_list_id)->get();
            if ($user_answer->count() != 0 ) {
                return $user_answer;
            } else {
                return 0;
            }
        //}
    }

    public function check_current_over()
    {
        $question_lists = Question_list::where('results_id', $this->result->id)->get();
        return $question_lists->count();
    }


    public function get_current_question()
    {
        $question_id = Question_list::where('results_id', $this->result->id)->where('counter', $this->quizprocessing->current)->get('question_id');
        return Question::find($question_id)->first();
    }    

    public function get_answers($question_id)
    {
        $answers = Question::find($question_id)->answer;

        return $answers;
    }

    private function question_list_generate($result_id, $quiz_id)
    {
        $all_quistions = Question::where('quiz_id', $quiz_id)->get();
        if ($all_quistions->count() == 0) {
            return redirect('/');
        }
        $i = 0;
        foreach ($all_quistions as $question) {        
            $i++;    
            $question_list = new Question_list;
            $question_list->results_id = $result_id;
            $question_list->question_id = $question->id;
            $question_list->counter = $i;
            $question_list->save();
           
        }
        return $this->result->question_list;
    }


    private function getUser() 
    {
        $user = User::find(Auth::user()->id);
        return $user;
    
    } 
    
    public function processing_check($quiz_id, $user_id)
    {
        $quizprocessing = Quizprocessing::where('quiz_id', $quiz_id)->where('user_id', $user_id)->get()->count();
        if ($quizprocessing > 0)
        {
            return true;
        }
        else {
            return false;
        }
    }

    public function check_acls($user_id, $quiz)
    {
        $profile = User::find($user_id)->profile;
        $grade = $profile->grade;
        $grade_acls = Quiz::find($quiz->id)->quizacl->where('grade', $grade);
        $quiz_count = $grade_acls->count();
        if ($quiz_count < 1)
            {
                return redirect('/');
            }
    }
}
