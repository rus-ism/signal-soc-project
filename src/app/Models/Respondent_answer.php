<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respondent_answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'respondent_id',
        'quiz_id',
        'question_id',
        'answer_id',
        'answered',
        'scope',
        'session',
    ];      

    public function respondent(): BelongsTo
    {
        return $this->belongsTo(Respondent::class); // Omit the second parameter if you're following convention
    }    

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class); // Omit the second parameter if you're following convention
    }  
    
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class); // Omit the second parameter if you're following convention
    } 

    public function answer()
    {
        return $this->belongsTo(Answer::class); // Omit the second parameter if you're following convention
    }     
        
}
