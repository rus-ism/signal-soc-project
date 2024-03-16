<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholler_count extends Model
{
    use HasFactory;


    protected $fillable = [
        'region_id',
        'school_id',
        'grade',
        'count',
        '5grade',
        '6grade',
        '7grade',
        '8grade',
        '9grade',
        '10grade',
        '11grade',
    ]; 


    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id'); // Omit the second parameter if you're following convention
    } 

    public function school()
    {
        return $this->belongsTo(School::class); // Omit the second parameter if you're following convention
    }  
}
