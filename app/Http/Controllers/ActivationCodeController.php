<?php

namespace App\Http\Controllers;

use App\Exports\ActivationCodesExport;
use App\Http\Requests\CheckCodeRequest;
use App\Http\Resources\CheckActivationCodeResource;
use App\HttpResponse\HTTPResponse;
use App\Models\ActivationCode;
use App\Http\Requests\StoreActivationCodeRequest;
use App\Http\Requests\UpdateActivationCodeRequest;
use App\Models\Course;
use App\Models\CourseCanActivated;
use App\Models\ExportableFile;
use App\Types\CodeType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;

class ActivationCodeController extends Controller
{
    use HTTPResponse;

    private function catchError ($th) {
        return $this->error($th->getMessage() , 500);
    }

    private function getRandomCode(){
        return strtoupper(Str::random(6));
    }

    public function store(StoreActivationCodeRequest $request){
        try {
            $type = $request->get('type');
            $courses = $request->get('courses');
            $quantity = intval($request->get('quantity'));

            $exportData = collect();
            DB::beginTransaction();
            if ($type === CodeType::SINGLE){
//                handle single code type
                if (count($courses) > 1){
                    return $this->error('please select just one course for single type or switch for shared type' , 422);
                }
                for ($i = 0 ; $i < $quantity ; $i++){
                    $code = $this->getRandomCode();
                    while (ActivationCode::where('code', $code)->exists()) {
                        $code = Str::random(6);
                    }
                    $newActivationCode = ActivationCode::create([
                        'code' => $code,
                        'times_of_usage' => count($courses),
                        'type' => $type
                    ]);
                    $exportData->push($newActivationCode);
                    CourseCanActivated::create([
                        'activation_code_id' => $newActivationCode->id,
                        'course_id' => $courses[0],
                    ]);
                }
            }else if ($type === CodeType::SHARED) {
                if (count($courses) <= 1){
                    return $this->error('please select more than one course for shared type or switch for single type' , 422);
                }
                for ($i = 0 ; $i < $quantity ; $i++){
                    $code = $this->getRandomCode();
                    while (ActivationCode::where('code', $code)->exists()) {
                        $code = Str::random(15);
                    }
                    $newActivationCode = ActivationCode::create([
                        'code' => $code,
                        'times_of_usage' => count($courses),
                        'type' => $type
                    ]);
                    $exportData->push($newActivationCode);
                    foreach ($courses as $course){
                        CourseCanActivated::create([
                            'activation_code_id' => $newActivationCode->id,
                            'course_id' => $course,
                        ]);
                    }
                }
            }else{
                for ($i = 0 ; $i < $quantity ; $i++) {
                    $code = $this->getRandomCode();
                    while (ActivationCode::where('code', $code)->exists()) {
                        $code = Str::random(15);
                    }
                    $newActivationCode = ActivationCode::create([
                        'code' => $code,
                        'times_of_usage' => $request->number_of_courses,
                        'type' => $type
                    ]);
                    $exportData->push($newActivationCode);
                }
            }
            $fileName = "";
            if ($request->title){
                $fileName = $request->title . 'xlsx';
            }else{
                $fileName = $quantity . 'activation codes from type ' . $type  . ' in ' . now()->format('Y-m-d')."xlsx";
            }
            $folder = 'excel_files';
            $filePath = $folder . '/' . $fileName;
            $coursesNameSpreatedByComma = collect($courses)->map(fn($course) =>
                Course::where('id' , $course)->pluck('name')
            )->implode('| ');
             ExportableFile::create([
               'path' => $fileName,
               'type_of_code' => $type,
               'courses_name' => $coursesNameSpreatedByComma
            ]);
            Excel::store(new ActivationCodesExport($exportData->toArray()), $filePath);
            DB::commit();
            return Storage::download($filePath);
        }catch (\Throwable $th){
            DB::rollBack();
            return $this->catchError($th);
        }
    }

    public function checkCode(CheckCodeRequest $request){
        try {
            $code = ActivationCode::where('code' , $request->code)->first();
            if (!$code){
                return $this->error(__('messages.activation_code_not_found') , 422);
            }
            return $this->success(CheckActivationCodeResource::make($code));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function getUnExpiredCodes(){
        try {
            $codes = ActivationCode::where('times_of_usage' , '>' , 0)->get();
            return $this->success($codes);
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }
}
