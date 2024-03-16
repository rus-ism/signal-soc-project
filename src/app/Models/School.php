<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class School extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'region_id',
        //'name',
        //'type',
        //'kind',
        //'locality',
    ];  

    public $translatedAttributes = ['name','type','kind','locality'];

    public function region()//: BelongsTo
    {
        return $this->belongsTo(Region::class); // Omit the second parameter if you're following convention
    }      

    public function respondent()
    {
        return $this->hasMany(Respondent::class);
    }   

    public function quiz_school_acl()
    {
        return $this->hasMany(Quiz_school_acl::class);
    }

    public function profile()
    {
        return $this->hasMany(Profile::class, 'scool_id', 'id');
    }

    public function started_quiz()
    {
        return $this->hasMany(Started_quiz::class);
    } 

    public function scholler_count()
    {
        return $this->hasMany(Scholler_count::class);
    }    
}
