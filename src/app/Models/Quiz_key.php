<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz_key extends Model
{
    use HasFactory;
    protected $fillable = [
        'quiz_id',
        'question_id',
        'answer_id',
        'scope',
        'coefficient',
        'scale_id',
    ]; 

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class); // Omit the second parameter if you're following convention
    } 

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class); // Omit the second parameter if you're following convention
    } 

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class); // Omit the second parameter if you're following convention
    } 

    public function key_scale()
    {
        return $this->hasMany(Key_scale::class);
    } 

}
