<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAdminRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Resources\UserResource;
use App\HttpResponse\HTTPResponse;
use App\Models\User;
use App\Types\UserType;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AuthController extends Controller
{
    use HTTPResponse;

    public function signup (SignUpRequest $request) {
        try {
            DB::beginTransaction();
            $user = User::create($request->only(['full_name' , 'phone' , 'password' , 'device_id']));
            DB::commit();
            return $this->success([
                "token" =>  $user->createToken("API TOKEN")->plainTextToken,
                "user" => UserResource::make($user)
            ] , trans('messages.create_new_user'));
        }catch (\Throwable $th){
            DB::rollBack();
            return $this->error("server error with message : " . $th->getMessage() , 500);
        }
    }

    public function loginAdmin(LoginAdminRequest $request){
        try {
            DB::beginTransaction();
            $user = User::where('phone', $request->phone)->first();
            if (!$user){
                return $this->error(
                    trans('messages.login_password_email_error')
                    , 401);
            }
            if (Gate::denies('login_admin')){
                return $this->error(
                    trans('messages.admin_permission')
                    , 403);
            }
            if (!Auth::attempt($request->only(['phone', 'password']))) {
                return $this->error(trans('messages.login_password_email_error')
                    , 401);
            }
            $token = $user->createToken('API TOKEN')->plainTextToken;
            DB::commit();
            return $this->success([
                "token" => $token,
                "user" => UserResource::make($user),
            ] , trans('messages.login' , [ 'name' => $user->full_name ]));
        }catch (\Throwable $th){
            DB::rollBack();
            return $this->error($th->getMessage() , 500);
        }
    }

    public function login(LoginUserRequest $request){
        try {
            DB::beginTransaction();
            $user = User::where('phone', $request->phone)->first();
            if (!$user){
                return $this->error(
                    trans('messages.login_password_email_error')
                , 401);
            }
            if ($user->is_blocked){
                return $this->error(trans('messages.account_blocked'), 403);
            }

//            maybe account created from the dashboard by admin and didn't have device_id
            if (boolval($user->device_id)){
                if ($user->device_id !== $request->device_id){
                    $user->update([
                        'is_blocked' => true,
                    ]);
                    $user->tokens()->delete();
                    DB::commit();
                    return $this->error(trans('messages.block_while_login_message'), 403);
                }
            }
            if (!Auth::attempt($request->only(['phone', 'password']))) {
                return $this->error(trans('messages.login_password_email_error')
                    , 401);
            }
            if (!$user->device_id){
                $user->update([
                    'device_id' => $request->device_id
                ]);
            }
            $token = $user->createToken('API TOKEN')->plainTextToken;
            DB::commit();
            return $this->success([
                "token" => $token,
                "user" => UserResource::make($user),
            ] , trans('messages.login' , [ 'name' => $user->full_name ]));
        }catch (\Throwable $th){
            DB::rollBack();
            return $this->error($th->getMessage() , 500);
        }
    }

    public function logout(){
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user->currentAccessToken()->delete();
            DB::commit();
            return $this->success($user , trans('messages.logout', ['name' => $user->full_name]));
        }catch (\Throwable $th){
            DB::rollBack();
            return $this->error($th->getMessage() , 500);
        }
    }

    public function refreshToken(){
        //TODO
//        refresh token logic
    }
}
