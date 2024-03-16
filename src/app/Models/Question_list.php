<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question_list extends Model
{
    use HasFactory;

    public function resulst(): BelongsTo
    {
        return $this->belongsTo(Resulst::class); // Omit the second parameter if you're following convention
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class); // Omit the second parameter if you're following convention
    } 

    public function user_answer()
    {
        return $this->hasMany(User_answer::class);
    }      
}
