<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function choices(){
        return $this->hasMany(Choice::class);
    }

    public function setImageAttribute ($image){
        $newImageName = uniqid() . '_' . 'image' . '.' . $image->extension();
        $image->move(public_path('question_images') , $newImageName);
        return $this->attributes['image'] =  '/'.'question_images'.'/' . $newImageName;
    }

    public function setClarificationImageAttribute($clarification_image){
        $newImageName = uniqid() . '_' . 'image' . '.' . $clarification_image->extension();
        $clarification_image->move(public_path('clarification_images') , $newImageName);
        return $this->attributes['clarification_image'] =  '/'.'clarification_images'.'/' . $newImageName;
    }
}
