<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Respondent extends Model
{
    use HasFactory;


    public function respondent_answer()
    {
        return $this->hasMany(Respondent_answer::class);
    }   

    public function respondent_result()
    {
        return $this->hasMany(Respondent_result::class, 'respondent_id', 'id');
    }     

    public function school()
    {
        return $this->belongsTo(School::class); // Omit the second parameter if you're following convention
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class); // Omit the second parameter if you're following convention
    }  
    
    public function user()
    {
        return $this->belongsTo(User::class); // Omit the second parameter if you're following convention
    } 
    
    public function started_quiz()
    {
        return $this->hasMany(Started_quiz::class);
    }     

}
