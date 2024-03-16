<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Key_scale extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_key_id',
        'scale_id',
    ];

    public function quiz_key()
    {
        return $this->belongsTo(Quiz_key::class); // Omit the second parameter if you're following convention
    } 

    public function scale()
    {
        return $this->belongsTo(Scale::class); // Omit the second parameter if you're following convention
    }     
}
