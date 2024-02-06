<?php

namespace App\Http\Controllers;

use App\Exports\ActivationCodesExport;
use App\HttpResponse\HTTPResponse;
use App\Models\ActivationCode;
use App\Http\Requests\StoreActivationCodeRequest;
use App\Http\Requests\UpdateActivationCodeRequest;
use App\Models\CourseCanActivated;
use App\Types\CodeType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ActivationCodeController extends Controller
{
    use HTTPResponse;

    private function catchError ($th) {
        return $this->error($th->getMessage() , 500);
    }

    public function store(StoreActivationCodeRequest $request){
        try {
//            $request->validated($request->only(['type' , 'courses' , 'courses.*' , 'quantity']));
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
                    $code = Str::random(15);
                    while (ActivationCode::where('code', $code)->exists()) {
                        $code = Str::random(15);
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
                    $code = Str::random(15);
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
                    $code = Str::random(15);
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
            $fileName = 'activation_codes_' . time() . '.xlsx';
            Excel::store(new ActivationCodesExport($exportData), public_path('excel_files').$fileName);
            DB::commit();
            return $this->success(null , 'created successfully');
        }catch (\Throwable $th){
            DB::rollBack();
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
