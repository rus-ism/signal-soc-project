<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

class QuizImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        /*
        $quizNameKz='4';
        $quizNameRu='4';
        $quizdescKz='4';
        $quizdescRu='4';
*/
        

        $i = 0; $j = 0;
        $questions = [];
        $rowcount = 0;
        //dd($collection);
        foreach ($collection as $row)
        {            
            $rowcount++;
            echo 'row -'.$row.'<br>';
            echo 'collection - '.$collection[$rowcount-1].'<br>';
            switch ($row[0]) {
                    case "nmm" : 
                        //dd($row);
                        $quizNameKz = $row[1];
                        $quizNameRu = $row[2];
                        break;
                    case 'desc' : 
                        $quizdescKz = $row[1];
                        $quizdescRu = $row[2];
                        break;   
                    case 'q' :
                        $questions[$i]['qKz'] = $row[1];
                        $questions[$i]['qRu'] = $row[2];
                        $i++;
                        break;
                    case 'a' : 
                        $questions[$i-1]['a'][$j]['txtKz'] = $row[1];
                        $questions[$i-1]['a'][$j]['txtRu'] = $row[2];
                        $questions[$i-1]['a'][$j]['sc'] = $row[3];
                        $j++;
                        break;                     
            }
            
        }

        if (!isset($quizNameKz)) {
            dd($rowcount);
        }
        $data = [
            'project_id' => 1,
            'type_id'    => 1,
            'ru' => [
                'quiz_name'       => $quizNameRu, 
                'quiz_description'       => $quizdescRu, 
            ],
            'kz' => [
                'quiz_name'       => $quizNameKz, 
                'quiz_description'       => $quizdescKz,
            ],
 
        ];


        $quiz = new Quiz($data);
        //$quiz->quiz_name        = $quizName;
        //$quiz->quiz_description = $quizdesc;
        //$quiz->project_id       = 1;
        //$quiz->type_id          = 1;
       // $quiz->save();
        //dd($quizNameKz);
        //$quiz->translateOrNew('kz')->quiz_name=$quizNameKz;
        //$quiz->translateOrNew('ru')->quiz_name = $quizNameRu;

        //$quiz->translateOrNew('kz')->quiz_description = $quizdescKz;
        //$quiz->translateOrNew('ru')->quiz_description = $quizdescRu;

        $quiz->save();
        foreach ($questions as $quest)
        {
            $quiestionData = [
                'quiz_id'  => $quiz->id,
                'question_type_id' => 1,
                'ru' => [
                    'text' => $quest['qRu'],
                ],
                'kz' => [
                    'text' => $quest['qKz'],
                ],
            ];
            $quiestion = new Question($quiestionData);
            //$quiestion->quiz_id          =  $quiz->id;
            //$quiestion->question_type_id = 1;
            #$quiestion->text             = $quest['q'];
            //$quiestion->save();
            //$quiestion->translateOrNew('kz')->text = $quest['qKz'];
            //$quiestion->translateOrNew('ru')->text = $quest['qRu'];
            $quiestion->save();
            foreach ($quest['a'] as $ans)
            {
                $answerData = [
                    'question_id' => $quiestion->id,
                    'scope' => $ans['sc'],
                    'ru'    =>  [
                        'text' => $ans['txtRu'],
                    ],
                    'kz' => [
                        'text' => $ans['txtKz'],
                    ],
                ];
                $answer = new Answer($answerData);
                //$answer->question_id   = $quiestion->id;
                //$answer->scope	       = $ans['sc'];
                #$answer->text	       = $ans['txt'];
                //$answer->save();
                //$answer->translateOrNew('kz')->text = $ans['txtKz'];
                //$answer->translateOrNew('ru')->text = $ans['txtRu'];
                $answer->save();
            }
        }

    }
    
}
