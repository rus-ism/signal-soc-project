<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScaleTranslation extends Model
{
    use HasFactory;

    protected $table = 'scale_translations';

    protected $fillable = [
        'title',
        'description',
    ];

    public $timestamps = false;

    
}
