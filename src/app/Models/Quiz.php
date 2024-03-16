<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Quiz extends Model
{
    use HasFactory, Translatable;

    protected $table = 'quizzes';

    

    protected $fillable = [
        //'quiz_name',
        //'quiz_description',
        //'quiz_instruction',
        'project_id',
        'type_id',
        'time_control',
        'max_score',
        'passing_score',
    ];

    public $translatedAttributes = ['quiz_name','quiz_description', 'quiz_instruction'];


    public function result_interpretation()
    {
        return $this->hasMany(Result_interpretation::class);
    }    

    public function quizacl()
    {
        return $this->hasMany(Quizacl::class, 'quiz_id');
    }

    public function quiz_school_acl()
    {
        return $this->hasMany(Quiz_school_acl::class);
    }

    public function question()
    {
        return $this->hasMany(Question::class);
    }   

    public function quizprocessing()
    {
        return $this->hasMany(Quizprocessing::class);
    } 
    
    public function resulst()
    {
        return $this->hasMany(Resulst::class);
    }  
    
    public function respondent_answer()
    {
        return $this->hasMany(Respondent_answer::class);
    }  
    
    public function respondent_result()
    {
        return $this->hasMany(Respondent_result::class);
    }
    
    public function started_quiz()
    {
        return $this->hasMany(Started_quiz::class);
    }     

    public function quiz_key()
    {
        return $this->hasMany(Quiz_key::class);
    }    

    public function scale()
    {
        return $this->hasMany(Scale::class);
    } 
}
