<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\Quiz;

class QuizController extends Controller
{
    public function description($id)
    {
        $profile = $this->getProfile();
        $quiz = Quiz::find($id);
     //   dd($quiz);
        return view('quiz-description', [
            'quiz'=>$quiz,
            'profile'=>$profile,
        ]);
    }


    private function checkQuizAcl($quiz)
    {
        ;
    }

    private function getProfile() 
    {
        $user = User::find(Auth::user()->id);
        return $user->profile;
    }      
}
