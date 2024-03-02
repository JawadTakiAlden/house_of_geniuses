<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivationCode extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function courseCanActivated(){
        return $this->hasMany(CourseCanActivated::class);
    }

    public function isExpired(){
        return intval($this->time_of_usage) === 0;
    }

}
