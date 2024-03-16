<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Region;
use App\Models\School;
use App\Models\Profile;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Quizacl;
use App\Models\Respondent;
use App\Models\Result_interpretation;

class Admin_testController extends Controller
{
    public function index(Request $request)
    {
        $quizzes = Quiz::all();
        $q = $quizzes->first();
        $quest = $q->question()->get()->count();
       //dd($quest);
        $data = [
            'quizzes' =>  $quizzes,
        ];
        return view('admin.quizzes', $data);
    }

    public function edit($quiz_id)
    {
        $quiz_array = [];
        $quiz = Quiz::find($quiz_id);
        $quiz_array['quiz'] = $quiz;
        $quiestion_array = [];
        $answers_array = [];
        $quiestions = $quiz->question()->get();
        $questions_count = $quiestions->count();
        if ($quiestions->count() > 0)
        {
            $quiestion_i = 0;
            foreach ($quiestions as $question)
            {
                $quiestion_array[$quiestion_i]['question'] = $question;
                
                $answers = $question->answer()->get();
                $quiestion_array[$quiestion_i]['answers'] = $answers;
                $quiestion_array[$quiestion_i]['answers_count'] = $answers->count()+2;
                $quiestion_i++;
            }
            $quiz_array['questions'] = $quiestion_array;
        }
        //dd($quiz_array);
        $interpret_array = [];
        $interpretations = $quiz->result_interpretation()->get();        
        if (($interpretations) AND($interpretations->count()>0))
        {
            ;
        }
        $grades = $quiz->quizacl()->get('grade');

        $data = [
            'quiz'  => $quiz,
            'quiz_array' => $quiz_array,
            'interpretations' => $interpretations,
            'grades' => $grades,
            'questions_count' => $questions_count,
        ];

        return view('admin.edit-test', $data);
    }

    public function get_edit_table($quiz_id)
    {
        $quiz_array = [];
        $quiz = Quiz::find($quiz_id);
        $quiz_array['quiz'] = $quiz;
        $quiestion_array = [];
        $answers_array = [];
        $quiestions = $quiz->question()->get();
        $questions_count = $quiestions->count();
        if ($quiestions->count() > 0)
        {
            $quiestion_i = 0;
            foreach ($quiestions as $question)
            {
                $quiestion_array[$quiestion_i]['question'] = $question;
                
                $answers = $question->answer()->get();
                $quiestion_array[$quiestion_i]['answers'] = $answers;
                $quiestion_array[$quiestion_i]['answers_count'] = $answers->count()+2;
                $quiestion_i++;
            }
            $quiz_array['questions'] = $quiestion_array;
        }
        //dd($quiz_array);
        $interpretations = $quiz->result_interpretation()->get();        
        if (($interpretations) AND($interpretations->count()>0))
        {
            ;
        }
        $grades = $quiz->quizacl()->get('grade');

        $data = [
            'quiz'  => $quiz,
            'quiz_array' => $quiz_array,
            'interpretations' => $interpretations,
            'grades' => $grades,
            'questions_count' => $questions_count,
        ];

        return view('admin.question-table-edit-test', $data);
    }



    public function get_interpret_table($quiz_id)
    {
        $quiz_array = [];
        $quiz = Quiz::find($quiz_id);
        $quiz_array['quiz'] = $quiz;
        $quiestion_array = [];
        $answers_array = [];
        $quiestions = $quiz->question()->get();
        if ($quiestions->count() > 0)
        {
            $quiestion_i = 0;
            foreach ($quiestions as $question)
            {
                $quiestion_array[$quiestion_i]['question'] = $question;
                
                $answers = $question->answer()->get();
                $quiestion_array[$quiestion_i]['answers'] = $answers;
                $quiestion_array[$quiestion_i]['answers_count'] = $answers->count()+2;
                $quiestion_i++;
            }
            $quiz_array['questions'] = $quiestion_array;
        }
        //dd($quiz_array);
        $interpretations = $quiz->result_interpretation()->get();        
        if (($interpretations) AND($interpretations->count()>0))
        {
            ;
        }
        $grades = $quiz->quizacl()->get('grade');

        $data = [
            'quiz'  => $quiz,
            'quiz_array' => $quiz_array,
            'interpretations' => $interpretations,
            'grades' => $grades,
        ];

        return view('admin.interpret-table-edit-test', $data);
    }


    public function get_quiz_table()
    {
        $quizzes = Quiz::all();
        $q = $quizzes->first();
        $quest = $q->question()->get()->count();
       //dd($quest);
        $data = [
            'quizzes' =>  $quizzes,
        ];
        return view('admin.quizzes-table', $data);
    }
    
    




    public function question_add(Request $request)
    {
       
        if ($request->input('action') != 'add') 
        {
            return json_encode([
                'error'   => 5,
                'comment' => 'no action',
            ]);
        };
        $error = 0;
        $quiz_id = $request->input('quiz_id');
        $question_text = $request->input('question_text');
        $question_text_kz = $request->input('question_text_kz');
        $question_description = $request->input('question_description');
        $question_description_kz = $request->input('question_description_kz');

        //$question = Question::where('quiz_id', $quiz_id)->where('text', $question_text)        
        $question = Question::create([
            'quiz_id'        => $quiz_id,
            //'text' => $question_text,
            //'description' => $question_description,
            'ru' => [
                'text' => $question_text,
                'description' => $question_description,
            ],
            'kz' => [
                'text' => $question_text_kz,
                'description' => $question_description_kz,                
            ],
            'question_type_id' => 1,
        ]);
        $data = [
            'error' => $error,
            'quiz_id'       => $quiz_id,
            'question_text' => $question_text,
            'question_description' => $question_description,
            'question_id' => $question->id,
        ];
        return json_encode($data);

    }



    public function question_update(Request $request)
    {
       
        if ($request->input('action') != 'edit') 
        {
            return json_encode([
                'error'   => 5,
                'comment' => 'no action',
            ]);
        };
        $error = 0;
        $quiz_id = $request->input('quiz_id');
        $question_id = $request->input('question_id');
        $question_text = $request->input('question_text');
        $question_text_kz = $request->input('question_text_kz');
        $question_description = $request->input('question_description');
        $question_description_kz = $request->input('question_description_kz');

        $question = Question::find($question_id);
        if (!$question) 
        {
            return json_encode([
                'error'   => 1,
                'comment' => 'question not found',
            ]);            
        }
        $question->translate('ru')->text = $question_text;
        $question->translate('kz')->text = $question_text_kz;
        $question->translate('ru')->description = $question_description;
        $question->translate('kz')->description = $question_description_kz;
        //$question->text = $question_text;
        //$question->description = $question_description;
        $question->save();
        $data = [
            'error' => $error,
            'quiz_id'       => $quiz_id,
            'question_text' => $question_text,
            'question_description' => $question_description,
            'question_id' => $question->id,
        ];
        return json_encode($data);

    }    

    public function question_delete(Request $request)
    {
       
        if ($request->input('action') != 'delete') 
        {
            return json_encode([
                'error'   => 5,
                'comment' => 'no action',
            ]);
        };
        $error = 0;
        $question_id = $request->input('question_id');
        $question = Question::find($question_id);
        if (!$question) {
            $data = [
                'error' => 1,
                'comment' => 'question not found',
            ];
            return json_encode($data);
        };

        $answers = Answer::where('question_id', $question_id);
        $answers->delete();
        $question->delete();


        //$question = Question::where('quiz_id', $quiz_id)->where('text', $question_text)

        $data = [
            'error' => $error,
            'question_id' => $question->id,
        ];
        return json_encode($data);

    }


    public function answer_add(Request $request)
    {
       
        if ($request->input('action') != 'add') 
        {
            return json_encode([
                'error'   => 5,
                'comment' => 'no action',
            ]);
        };
        $error = 0;
        $question_id = $request->input('question_id');
        $answer_text = $request->input('answer_text');
        $answer_text_kz = $request->input('answer_text_kz');
        $answer_scope = $request->input('answer_scope');

        //$question = Question::where('quiz_id', $quiz_id)->where('text', $question_text)
        $answer = Answer::create([
            'question_id'        => $question_id,
            //'text' => $answer_text,
            'ru' => [
                'text' => $answer_text,
            ],
            'kz' => [
                'text' => $answer_text_kz,               
            ],
            'scope' => $answer_scope,
        ]);
        $data = [
            'error' => $error,
            'question_id' => $question_id,
            'answer_text' => $answer_text,
            'answer_id' => $answer->id,
        ];
        return json_encode($data);

    } 



    public function answer_update(Request $request)
    {
       
        if ($request->input('action') != 'edit') 
        {
            return json_encode([
                'error'   => 5,
                'comment' => 'no action',
            ]);
        };
        $error = 0;
        $question_id = $request->input('question_id');
        $answer_id = $request->input('answer_id');
        $answer_text = $request->input('answer_text');
        $answer_text_kz = $request->input('answer_text_kz');
        $answer_scope = $request->input('answer_scope');

        //$question = Question::where('quiz_id', $quiz_id)->where('text', $question_text)
        $answer = Answer::find($answer_id);
        if (!$answer_id)
        {
            return json_encode([
                'error'   => 1,
                'comment' => 'answer not found',
            ]);             
        }        
        //$answer->text = $answer_text;
        $answer->translate('ru')->text = $answer_text;
        $answer->translate('kz')->text = $answer_text_kz;
        $answer->scope = $answer_scope;
        $answer->save();

        $data = [
            'error' => $error,
            'question_id' => $question_id,
            'answer_text' => $answer_text,
            'answer_id' => $answer->id,
        ];
        return json_encode($data);

    } 


    
    public function answer_delete(Request $request)
    {
       
        if ($request->input('action') != 'delete') 
        {
            return json_encode([
                'error'   => 5,
                'comment' => 'no action',
            ]);
        };
        $error = 0;
        $answer_id = $request->input('answer_id');
        $answer = Answer::find($answer_id);
        if (!$answer) {
            $data = [
                'error' => 1,
                'comment' => 'question not found',
            ];
            return json_encode($data);
        };

        $answer->delete();

        //$question = Question::where('quiz_id', $quiz_id)->where('text', $question_text)

        $data = [
            'error' => $error,
            'question_id' => $answer->id,
        ];
        return json_encode($data);

    }    


    public function quizacl_change(Request $request)
    {
       
        $error = 0;
        $action = $request->input('action');
        $grade = $request->input('grade');
        $quiz_id = $request->input('quiz_id');
        $quiz_acl = Quizacl::where('quiz_id', $quiz_id)->where('grade',$grade)->get();
        if ($action == 'set')
        {
            if ($quiz_acl->count() != 0) {
                return json_encode([
                    'error'   => 1,
                    'comment' => 'acl alreade seted',
                ]);
            }
            $acl = Quizacl::create([
                'quiz_id' => $quiz_id,
                'grade' => $grade,
            ]);
            $data = [
                'error' => $error,
                'acl_id' => $acl->id,
            ];
        }
        if ($action == 'unset')
        {
            if ($quiz_acl->count() == 0) {
                return json_encode([
                    'error'   => 1,
                    'comment' => 'acl alreade NOT seted',
                ]);
            }
            $acl = Quizacl::where('quiz_id',$quiz_id)->where('grade', $grade)->delete();
            $data = [
                'error' => $error,
            ];
            
        };
        return json_encode($data);

    } 



    public function interpret_add(Request $request)
    {
       
        if ($request->input('action') != 'add') 
        {
            return json_encode([
                'error'   => 5,
                'comment' => 'no action',
            ]);
        };
        $error = 0;
        $quiz_id = $request->input('quiz_id');
        $from = $request->input('from');
        $to = $request->input('to');
        $text_ru = $request->input('text_ru');
       // return json_encode($text_ru);
        $text_kz = $request->input('text_kz');
        $assessment = $request->input('assessment');
        //$question = Question::where('quiz_id', $quiz_id)->where('text', $question_text)
        $interpret = Result_interpretation::create([
            'quiz_id'        => $quiz_id,
            //'text' => $text,
            'ru' => [
                'text' => $text_ru,
            ],
            'kz' => [
                'text' => $text_kz,
            ],
            'from' => $from,
            'to' => $to,
            'assessment' => $assessment,
        ]);
        $data = [
            'error' => $error,
            'quiz_id'       => $quiz_id,
            'text' => $text_ru,
            'interpret_id' => $interpret->id,
        ];
        return json_encode($data);

    }

    public function interpret_delete(Request $request)
    {
       
        if ($request->input('action') != 'delete') 
        {
            return json_encode([
                'error'   => 5,
                'comment' => 'no action',
            ]);
        };
        $error = 0;
        $interpret_id = $request->input('interpret_id');
        $interpret = Result_interpretation::find($interpret_id);
        if (!$interpret) {
            $data = [
                'error' => 1,
                'comment' => 'question not found',
            ];
            return json_encode($data);
        };

        $interpret->delete();



        $data = [
            'error' => $error,
            'interpret_id' => $interpret->id,
        ];
        return json_encode($data);

    }    



    public function quiz_add(Request $request)
    {
       
        if ($request->input('action') != 'add') 
        {
            return json_encode([
                'error'   => 5,
                'comment' => 'no action',
            ]);
        };
        $error = 0;
        $title = $request->input('title');
        $title_kz = $request->input('title_kz');
        $desc = $request->input('desc');
        $desc_kz = $request->input('desc_kz');
        $inst = $request->input('inst');
        $inst_kz = $request->input('inst_kz');

        //$question = Question::where('quiz_id', $quiz_id)->where('text', $question_text)
        $quiz = Quiz::create([
            //'quiz_name'        => $title,
            //'quiz_description' => $desc,
            //'quiz_instruction' => $inst,
            'ru' => [
                'quiz_name'        => $title,
                'quiz_description' => $desc,
                'quiz_instruction' => $inst,                
            ],
            'kz' => [
                'quiz_name'        => $title_kz,
                'quiz_description' => $desc_kz,
                'quiz_instruction' => $inst_kz,
            ],
            'project_id' => 1,
            'type_id'   => 1,
        ]);
        $data = [
            'error' => $error,
            'quiz_id' => $quiz->id,
        ];
        return json_encode($data);

    }


}
