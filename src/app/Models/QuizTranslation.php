<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizTranslation extends Model
{
    use HasFactory;

    protected $table = 'quiz_translations';

    protected $fillable = [
        'quiz_name',
        'quiz_description',
        'quiz_instruction',
    ];

    public $timestamps = false;

}
