<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use DB;

class AdminController extends Controller
{
    /**
     * Profile Update
     * 
     * @author Vishal Soni
     * @package Admin
     * @param Request $request
     * @return Json
     *     
     */

    public function updateAccountSetting(Request $request) {
        try {
            $rule = [
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'phone' => 'required|unique:users,phone,' . $request->id .',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            $user = User::find(auth()->user()->id);

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone = $request->phone;

            $user->save();

            return jsonResponse(status: true, success: __('message.update', ['Personal Details']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Change Password
     * 
     * @author Vishal Soni
     * @package Admin
     * @param Request $request
     * @return Json
     *     
     */

    public function changePassword(Request $request){
        try {
            $rule = [
                'current_password' => 'required',
                'new_password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|same:confirm_password',
                'confirm_password' => 'required|min:8'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            $user  = User::select('password')->where('id', auth()->user()->id)->first();

            if(Hash::check($request->current_password, $user->password)){
                User::where('id', auth()->user()->id)->update(['password' => Hash::make($request->confirm_password)]);

                return jsonResponse(status: true, success: __('message.update', ['Password']));
            }else{
                return jsonResponse(status: false, error: __('message.password_not_match', ['Current Password']));
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Direct Login
     * 
     * @package Admin
     * @author  Vishal Soni
     * @param   Request $request
     * @return  JsonResponse
     */

    public function directLogin(Request $request)
    {
        try {
            if ($request->id) {
                $user = User::find($request->id);
                if ($user) {
                    if ($user->status == 'active') {
                        $token = $user->createToken('auth_token')->plainTextToken;
                        $token = explode('|', $token);
                        $user->accessToken = $token[1];
                        return returnResponse(data: $user);
                    } else {
                        return returnResponse(error: __('message.inactive_account', ['User']));
                    }
                } else {
                    return returnResponse(error: __('message.not_exists', ['User']));
                }
            } else {
                return returnResponse(error: __('message.error.500'));
            }
        } catch (\Throwable $th) {
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }
}
