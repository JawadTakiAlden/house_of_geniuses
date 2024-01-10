<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function accountInrolments(){
        return $this->hasMany(AccountInrolment::class);
    }

    public function courseCategorys(){
        return $this->hasMany(CourseCategory::class);
    }


    public function setImageAttribute ($image){
        if (!$image){
            return $this->attributes['image'] = fake()->imageUrl;
        }
        $newImageName = uniqid() . '_' . 'image' . '.' . $image->extension();
        $image->move(public_path('course_images') , $newImageName);
        return $this->attributes['image'] =  '/'.'course_images'.'/' . $newImageName;
    }

    public function categories(){
        return $this->belongsToMany(Category::class , 'course_categories');
    }

    public function teachers(){
        return $this->belongsToMany(User::class , 'course_teachers' , 'course_id', 'teacher_id');
    }

    public function teacherCourse(){
        return $this->hasMany(CourseTeacher::class);
    }

    public function chapters(){
        return $this->hasMany(Chapter::class);
    }

    public function courseValues(){
        return $this->hasMany(CourseValue::class);
    }

    public function students(){
        return $this->belongsToMany(User::class , 'account_inrolments');
    }

    public function visibleChapters(){
        return $this->chapters()->where('is_visible' , true);
    }
}
