<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\HttpResponse\HTTPResponse;
use App\Models\AccountInrolment;
use App\Models\UserWatch;
use Illuminate\Http\Request;

class UserWatchController extends Controller
{
    use HTTPResponse;
    public function makeNewWatch($lesionID){
        try {
            $lesion = HelperFunction::getLesionByID($lesionID);
            if (!$lesion){
                return $this->error('lesion not found in our system' , 404);
            }
            if (
                UserWatch::where('user_id' , auth()->user()->id)->where('lesion_id' , $lesionID)->exists()
                || !AccountInrolment::where('course_id' , $lesion->course_id)->where('user_id' , auth()->user()->id)->exists()
            ){
                return $this->success(null , 'watched successfully registered');
            }
            UserWatch::create([
               'user_id' =>  auth()->user()->id,
                'lesion_id' => $lesionID
            ]);
            return $this->success(null , 'watched successfully registered');
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    private function catchError ($th) {
        return $this->error($th->getMessage() , 500);
    }
}
