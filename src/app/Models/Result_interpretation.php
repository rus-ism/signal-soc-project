<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Result_interpretation extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'quiz_id',
        //'text',
        'from',
        'to',
        'assessment',
    ];

    public $translatedAttributes = ['text'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }      
}
