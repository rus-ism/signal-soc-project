<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public function hasRole()
    {
        if($this->role === 2){
            return 2;
          }else if($this->role === 1){
            return 1;
          }else if($this->role === 3){
            return 3;
          } else if($this->role === 5){
            return 5;
          } else {
            return false;
          }
    }


    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }    

    public function quizprocessing()
    {
        return $this->hasMany(Quizprocessing::class);
    }

    public function resulst()
    {
        return $this->hasMany(Resulst::class);
    }  
    
    public function user_request()
    {
        return $this->hasOne(User_request::class);
    }     
    public function respondent()
    {
        return $this->hasOne(Respondent::class);
    } 
    
}
