<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'name',
    ];   

    public function school()
    {
        return $this->hasMany(School::class);
    }    
    
    public function respondent()
    {
        return $this->hasMany(Respondent::class);
    }   
    
    public function profile()
    {
        return $this->hasMany(Profile::class);
    } 

    public function user_request()
    {
        return $this->hasMany(User_request::class);
    } 

    public function scholler_count()
    {
        return $this->hasMany(Scholler_count::class);
    } 
    /*
    public function checkIfEntryExist($name)
    {

    }
    */
}
