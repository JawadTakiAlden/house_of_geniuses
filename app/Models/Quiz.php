<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function forUserQuestions(){
        return $this->belongsToMany(Question::class , 'question_quizzes')->wherePivot('is_visible' , 1);
    }
    public function forAdminQuestions(){
        return $this->belongsToMany(Question::class , 'question_quizzes');
    }
}
