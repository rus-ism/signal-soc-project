<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'result_id',
        'question_list_id',
        'answer_id',
        'text',
    ];

    public function resulst()
    {
        return $this->belongsTo(Resulst::class);
    }      

    public function question()
    {
        return $this->belongsTo(Question::class);
    }   
    
    public function question_list()
    {
        return $this->belongsTo(Question_list::class);
    }    
}
