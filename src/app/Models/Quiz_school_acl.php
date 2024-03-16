<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz_school_acl extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'school_id',
    ];       

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }    
}
