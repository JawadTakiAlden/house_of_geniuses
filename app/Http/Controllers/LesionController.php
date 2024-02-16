<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Resources\LesionResource;
use App\HttpResponse\HTTPResponse;
use App\Models\Lesion;
use App\Http\Requests\StoreLesionRequest;
use App\Http\Requests\UpdateLesionRequest;
use Illuminate\Support\Facades\Storage;
use Vimeo\Vimeo;

class LesionController extends Controller
{
    use HTTPResponse;
    private function catchError ($th) {
        return $this->error($th->getMessage() , 500);
    }

    public function getAll($chpaterID){
        try {
            $chapter = HelperFunction::getChapterByID($chpaterID);
            if (!$chapter){
                return $this->error('chapter dose\'nt found in our system' , 404);
            }
            $lesions = Lesion::where('chapter_id' , $chpaterID)->get();
            return $this->success(LesionResource::collection($lesions));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function getVisible($chpaterID){
        try {
            $chapter = HelperFunction::getChapterByID($chpaterID);
            if (!$chapter){
                return $this->error('chapter dose\'nt found in our system' , 404);
            }
            $lesions = Lesion::where('chapter_id' , $chpaterID)->where('is_visible' , true)->get();
            return $this->success(LesionResource::collection($lesions));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function switchVisibility($lesionID){
        try {
            $lesion = HelperFunction::getLesionByID($lesionID);
            if (!$lesion){
                return $this->error('lesion dose\'nt found in our system' , 404);
            }
            $lesion->update([
               'is_visible' => !$lesion->is_visible
            ]);
            return $this->success(
                LesionResource::make($lesion) ,
                $lesion->title . ' changed to be ' . $lesion->is_visible ? 'visible' : 'invisible' . ' successfully'
            );
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function delete($lesionID){
        try {
            $lesion = HelperFunction::getLesionByID($lesionID);
            if (!$lesion){
                return $this->error('lesion dose\'nt found in our system' , 404);
            }
            $lesion->delete();
            return $this->success(
                LesionResource::make($lesion) ,
                $lesion->title . ' deleted successfully'
            );
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function store(StoreLesionRequest $request){
        try {
            $type = $request->type;
            if ($type === 'pdf'){
                $pdfFile = $request->file('pdfFile');

                $pdfFile->store('pdf_lesions', 'public');
                $filePath = Storage::putFile('pdf_lesions', $pdfFile);
                $lesion = Lesion::create([
                    'title' => $request->title ?? $pdfFile->getClientOriginalName(),
                    'link' => $filePath,
                    'time' => $request->time ?? 0,
                    'is_open' => $request->is_open,
                    'is_visible' => $request->is_visible,
                    'type' => $type,
                    'chapter_id' => $request->chapter_id
                ]);

                return $this->success(
                    LesionResource::make($lesion),
                    $lesion->title . ' added successfully to ' . $lesion->chapter->name . ' in ' . $lesion->chapter->course->name . ' course'
                );
            }else if ($type === 'video'){
                $client = new Vimeo("f5558ea3eb98817fbe2126ae2541b6e11bfb0e44"
                    , "bXD6qM5hnj79bWmodVz7vIw4ZS1maOBgVUl8N5cWnV2r6aTMZ6QNI3UT5LwW+lOSLRH2vvfoE1xoAuKfsjNbe+pEjXwaSXt+7XCPxaJzNWQJi/GWUbkd3o4DcM307fP9",
                    "0090c4cf290951714e846847fe8b2fe5");

                $response = $client->request($request->videoURI, array(), 'GET');
                $responseData = $response['body'];
                $lesion = Lesion::create($request->only([
                    'title' => $request->title ?? $responseData['name'],
                    'link' => $responseData['player_embed_url'],
                    'time' => intval($responseData['duration']),
                    'is_open' => $request->is_open,
                    'is_visible' => $request->is_visible,
                    'type' => $type,
                    'chapter_id' => $request->chapter_id
                ]));
                return $this->success(LesionResource::make($lesion) , $lesion->title . ' added successfully to ' . $lesion->chapter->name . ' in ' . $lesion->chapter->course->name .' course');
            }else{
                return $this->error('you are provide unknown type of lesion' , 422);
            }
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function update(UpdateLesionRequest $request , $lesionID){
        try {
            $lesion = HelperFunction::getLesionByID($lesionID);
            if (!$lesion){
                return $this->error('lesion dose\'nt found in our system' , 404);
            }
            $lesion->update($request->only(['title' , 'link' , 'is_open' , 'is_visible' , 'type']));
            return $this->success(LesionResource::make($lesion) , $lesion->title . ' updated successfully');
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function getPdfLesion($path){
        try {
            $file = Storage::exists($path);
            if (!$file){
                return $this->error('file requested not found' , 404);
            }
            return Storage::get($path);
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }
}
