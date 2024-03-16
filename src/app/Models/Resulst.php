<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resulst extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'quiz_id',
        'count',
        'answered_count',
        'scope',
        'end_at',
        'fio',
    ];     

    public function quizprocessing(): HasOne
    {
        return $this->hasOne(Quizprocessing::class, 'results_id');
    }       

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // Omit the second parameter if you're following convention
    }    

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class); // Omit the second parameter if you're following convention
    }

    public function question_list()
    {
        return $this->hasMany(Question_list::class, 'results_id');
    }      
    
    public function user_answer()
    {
        return $this->hasMany(User_answer::class, 'result_id');
    }      
}
