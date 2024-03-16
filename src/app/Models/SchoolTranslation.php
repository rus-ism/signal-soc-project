<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolTranslation extends Model
{
    use HasFactory;

    protected $table = 'school_translations';

    protected $fillable = [
        'name',
        'type',
        'kind',
        'locality',
    ];

    public $timestamps = false;        
}
