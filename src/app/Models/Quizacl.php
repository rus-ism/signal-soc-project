<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizacl extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'grade',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
