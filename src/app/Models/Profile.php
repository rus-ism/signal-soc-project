<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role_id',
        'region_id',
        'scool_id',
        'scool_name',
        'grade',
        'litera',
        'fio',
    ];    

    public function user()
    {
        return $this->belongsTo(User::class); // Omit the second parameter if you're following convention
    }    
    
    public function region()
    {
        return $this->belongsTo(Region::class); // Omit the second parameter if you're following convention
    }      

    public function school()
    {
        return $this->belongsTo(School::class, 'scool_id', 'id'); // Omit the second parameter if you're following convention
    }  

    public function user_request()
    {
        return $this->hasMany(User_request::class);
    } 
}
