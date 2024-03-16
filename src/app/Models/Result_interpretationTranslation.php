<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result_interpretationTranslation extends Model
{
    use HasFactory;

    protected $table = 'result_interpretation_translations';

    protected $fillable = [
       'text',
    ];   
    
    public $timestamps = false;
}
