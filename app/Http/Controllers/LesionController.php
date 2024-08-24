<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\ReOrderLesionsRequest;
use App\Http\Resources\LesionResource;
use App\HttpResponse\HTTPResponse;
use App\Models\Lesion;
use App\Http\Requests\StoreLesionRequest;
use App\Http\Requests\UpdateLesionRequest;
use App\Types\LesionType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Vimeo\Vimeo;

class LesionController extends Controller
{
    use HTTPResponse;
    private Vimeo $client;

    public function __construct()
    {
        $this->client1 = new Vimeo(env('VIMEO_CLIENT_ID')
            , env('VIMEO_CLIENT_SECRET'),
            env('VIMEO_ACCESS_TOKEN'));
        $this->client2 = new Vimeo(env('VIMEO_CLIENT_ID_TWO')
            , env('VIMEO_CLIENT_SECRET_TWO'),
            env('VIMEO_ACCESS_TOKEN_TWO'));
    }
    public function getAll($chpaterID){
        try {
            $chapter = HelperFunction::getChapterByID($chpaterID);
            if (!$chapter){
                return HelperFunction::notFoundResponce();
            }
            $lesions = Lesion::where('chapter_id' , $chpaterID)->get();
            return $this->success(LesionResource::collection($lesions));
        }catch (\Throwable $th){
            return HelperFunction::ServerErrorResponse();
        }
    }

    public function getVisible($chpaterID){
        try {
            $chapter = HelperFunction::getChapterByID($chpaterID);
            if (!$chapter){
                return HelperFunction::notFoundResponce();
            }
            $lesions = Lesion::where('chapter_id' , $chpaterID)->where('is_visible' , true)->get();
            return $this->success(LesionResource::collection($lesions));
        }catch (\Throwable $th){
            return HelperFunction::ServerErrorResponse();
        }
    }

    public function switchVisibility($lesionID){
        try {
            $lesion = HelperFunction::getLesionByID($lesionID);
            if (!$lesion){
                return HelperFunction::notFoundResponce();
            }
            $lesion->update([
               'is_visible' => !$lesion->is_visible
            ]);
            return $this->success( LesionResource::make($lesion) , __("messages.lesion_controller.visibility_switch"));
        }catch (\Throwable $th){
            return HelperFunction::ServerErrorResponse();
        }
    }

    public function reOrderLesions(ReOrderLesionsRequest $request){
        try {
            DB::beginTransaction();
            if ($request->lesions){
                foreach ($request->lesions as $lesion){
                    Lesion::where('id' , $lesion['id'])->update([
                        'sort' => $lesion['sort']
                    ]);
                }
            }
            DB::commit();
            return $this->success( null,'lesion re-ordered successfully');
        }catch (\Throwable $th){
            DB::rollBack();
            return HelperFunction::ServerErrorResponse();
        }
    }

    public function delete($lesionID){
        try {
            $lesion = HelperFunction::getLesionByID($lesionID);
            if (!$lesion){
                return HelperFunction::notFoundResponce();
            }
            if ($lesion->type === 'pdf'){
                Storage::delete($lesion->link);
            }
            $lesion->delete();
            return $this->success(LesionResource::make($lesion) ,__("messages.lesion_controller.delete"));
        }catch (\Throwable $th){
            return HelperFunction::ServerErrorResponse();
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

                return $this->success(  LesionResource::make($lesion),$lesion->title . __("messages.lesion_controller.create"));
            }
            else if ($type === 'video'){
                $response = $this->client1->request($request->videoURI, array(), 'GET');
                if (intval($response['status']) === 200){
                    $lesion = $this->createVideo($request , $response['body']);
                }else{
                    $response = $this->client2->request($request->videoURI, array(), 'GET');
                    if (intval($response['status']) === 200){
                        $lesion = $this->createVideo($request , $response['body']);
                    }else{
                        return $this->error("sorry we can't find your video" , 404);
                    }
                }
                return $this->success(LesionResource::make($lesion) , __("messages.lesion_controller.create"));
            }else{
                return $this->error(__("messages.error.unknown_lesion_type") , 422);
            }
        }catch (\Throwable $th){
            return HelperFunction::ServerErrorResponse();
        }
    }

    private function createVideo($request , $responseData) {
        $date = [
            'description' => $responseData['description'],
            'link' => $responseData['uri'],
            'time' => intval($responseData['duration']) / 60,
            'is_open' => $request->is_open,
            'is_visible' => $request->is_visible,
            'type' => 'video',
            'chapter_id' => $request->chapter_id
        ];
        if ($request->title){
            $date = array_merge($date , ['title' => $request->title]);
        }else{
            $date = array_merge($date , ['title' => $responseData['name']]);
        }
        $lesion = Lesion::create($date);

        return $lesion;
    }

    public function update(UpdateLesionRequest $request , $lesionID){
        try {
            $lesion = HelperFunction::getLesionByID($lesionID);
            if (!$lesion){
                return HelperFunction::notFoundResponce();
            }
            if ($lesion->type === LesionType::VIDEO){
                $data = [
                    'is_visible' => $request->is_visible,
                    'is_open' => $request->is_open,
                ];
                if ($request->videoURI){
                    $response = $this->client1->request($request->videoURI, array(), 'GET');
                    if (intval($response['status']) === 200){
                         $data = array_merge($data , $this->updatedVideoData($response , $request));
                    }else{
                        $response = $this->client1->request($request->videoURI, array(), 'GET');
                        if (intval($response['status']) === 200){
                            $data = array_merge($data , $this->updatedVideoData($response , $request));
                        }
                        else{
                            return $this->error('we cant update your video' , 400);
                        }
                    }
                }
                else{
                    $data['title'] = $request->title;
                }
                $lesion->update($data);
            }else {
                $data = [
                    'is_visible' => $request->is_visible,
                    'is_open' => $request->is_open,
                    'time' => $request->time,
                    'description' => $request->description
                ];

                if ($request->pdfFile){
                    Storage::delete($lesion->link);
                    $pdfFile = $request->file('pdfFile');
                    $pdfFile->store('pdf_lesions', 'public');
                    $filePath = Storage::putFile('pdf_lesions', $pdfFile);
                    $data['link'] = $filePath;
                    if (!$request->title){
                        $data['title'] = $pdfFile->getClientOriginalName();
                    }else{
                        $data['title'] = $request->title;
                    }
                }else{
                    $data['title'] = $request->title;
                }
                $lesion->update($data);
            }
            return $this->success(LesionResource::make($lesion) , __("messages.lesion_controller.update"));
        }catch (\Throwable $th){
            return HelperFunction::ServerErrorResponse();
        }
    }

    private function updatedVideoData ($videoResponse , $request) {
        $responseData = $videoResponse['body'];
        $data = [
            'description' => $responseData['description'],
            'time' => intval($responseData['duration']) / 60,
            'link' => $responseData['uri'],
        ];
        if (!$request->title){
            $data['title'] = $responseData['name'];
        }else{
            $data['title'] = $request->title;
        }
        return $data;
    }

    public function getPdfLesion($path){
        try {
            $file = Storage::exists($path);
            if (!$file){
                return HelperFunction::notFoundResponce();
            }
            return Storage::get($path);
        }catch (\Throwable $th){
            return HelperFunction::ServerErrorResponse();
        }
    }
}
