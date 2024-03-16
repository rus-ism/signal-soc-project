<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respondent_result extends Model
{
    use HasFactory;

    protected $fillable = [
        'respondent_id',
        'quiz_id',
        'count',
        'answered_count',
        'scope',
        'session',
    ];

    public function respondent()
    {
        return $this->belongsTo(Respondent::class); // Omit the second parameter if you're following convention
    } 
    
    public function quiz()
    {
        return $this->belongsTo(Quiz::class); // Omit the second parameter if you're following convention
    } 
    
    
}
