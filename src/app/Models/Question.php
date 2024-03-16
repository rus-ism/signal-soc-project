<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Question extends Model
{
    use HasFactory, Translatable;

    protected $table = 'questions';

    protected $fillable = [
        'quiz_id',
        'question_type_id',
        //'text',
        //'description',
        'image',
        'counter',
    ];

    public $translatedAttributes = ['text','description'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }    
    public function answer()
    {
        return $this->hasMany(Answer::class);
    } 

    public function question_list()
    {
        return $this->hasMany(Question_list::class);
    }    

    public function user_answer()
    {
        return $this->hasMany(User_answer::class);
    }  
    
    public function respondent_answer()
    {
        return $this->hasMany(Respondent_answer::class);
    }      

    public function quiz_key()
    {
        return $this->hasMany(Quiz_key::class);
    }
}
