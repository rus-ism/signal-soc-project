<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Started_quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'respondent_id',
        'school_id',
    ]; 

    public function school()
    {
        return $this->belongsTo(School::class); // Omit the second parameter if you're following convention
    }   

    public function respondent()
    {
        return $this->belongsTo(Respondent::class); // Omit the second parameter if you're following convention
    } 

    public function quiz()
    {
        return $this->belongsTo(Quiz::class); // Omit the second parameter if you're following convention
    }     
}
