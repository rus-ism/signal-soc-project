<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizprocessing extends Model
{
    use HasFactory;
    protected $fillable = [
        'quiz_id',
        'user_id',
        'results_id',
        'current',
    ];     

    public function resulst(): BelongsTo
    {
        return $this->belongsTo(Resulst::class); // Omit the second parameter if you're following convention
    }   
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // Omit the second parameter if you're following convention
    }   
    
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class); // Omit the second parameter if you're following convention
    }      
}
