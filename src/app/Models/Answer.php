<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Answer extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'question_id',
        'is_right',
        'scope',
        //'text',
        'image',
        'counter',
    ];

    public $translatedAttributes = ['text'];

    public function question()
    {
        return $this->belongsTo(Question::class);
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
