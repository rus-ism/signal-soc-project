<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerTranslation extends Model
{
    use HasFactory;

    protected $table = 'answers_translations';

    protected $fillable = [
        'text',
    ];

    public $timestamps = false;    
}
