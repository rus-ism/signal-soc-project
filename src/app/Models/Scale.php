<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Scale extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'quiz_id',
        'coefficient',
        'max',
    ];

    public $translatedAttributes = ['title','description'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class); // Omit the second parameter if you're following convention
    } 

    public function key_scale()
    {
        return $this->hasMany(Key_scale::class);
    }     
}
