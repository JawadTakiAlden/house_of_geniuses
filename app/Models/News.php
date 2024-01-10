<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function setImageAttribute ($image){
        if (!$image){
            return $this->attributes['image'] = fake()->imageUrl;
        }
        $newImageName = uniqid() . '_' . 'image' . '.' . $image->extension();
        $image->move(public_path('news_images') , $newImageName);
        return $this->attributes['image'] =  '/'.'news_images'.'/' . $newImageName;
    }
}
