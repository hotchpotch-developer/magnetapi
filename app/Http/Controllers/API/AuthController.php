<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Login for all roles
     *
     * @author Vishal Soni
     * @package Auth
     * @param Request $request
     * @return JSON
     *
    */

    protected function login(Request $request){
        try {
            $rule = [
                'email' => 'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
                'password' => 'required|min:8|max:16',
            ];

            if ($errors = isValidatorFails($request, $rule))
                return $errors;

            $this->incrementLoginAttempts($request);

            if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                $seconds = $this->limiter()->availableIn($this->throttleKey($request));

                return jsonResponse(status: 401, error: trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]));
            }

            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                return self::getUserInfo($user);
            } else {
                return jsonResponse(status: 401, error: trans('auth.failed'));
            }
        } catch (\Throwable $th) {
           return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    # Determine if the user has too many failed login attempts.
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $this->maxAttempts()
        );
    }

    # Increment the login attempts for the user.
    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttleKey($request), $this->decayMinutes() * 60
        );
    }

    # Clear the login locks for the given user credentials.
    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    # Fire an event when a lockout occurs.
    protected function fireLockoutEvent(Request $request)
    {
        event(new Lockout($request));
    }

    # Get the throttle key for the given request.
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    # Get the rate limiter instance.
    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    # Get the maximum number of attempts to allow.
    public function maxAttempts()
    {
        return property_exists($this, 'maxAttempts') ? $this->maxAttempts : 5;
    }

    # Get the number of minutes to throttle
    public function decayMinutes()
    {
        return property_exists($this, 'decayMinutes') ? $this->decayMinutes : 1;
    }

    private static function getUserInfo($user, $token = '')
    {
        if ($user->status == 'active') {
            if (!$token) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $token = explode('|', $token);
                $user->access_token = $token[1];
            } else {
                $user->access_token = $token;
            }

            $user->first_name = $user->first_name;
            $user->last_name = $user->last_name;

            return jsonResponse(status: 200, data: $user->only('id', 'role_id', 'first_name', 'last_name', 'email', 'phone', 'profile_image', 'status', 'access_token'));
        } else {
            if ($user->status == 'inactive') {
                return jsonResponse(status: 200, error: __('auth.block'));
            } else {
                return jsonResponse(status: 200, error: __('auth.email_verification'));
            }
        }
    }

    protected function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return jsonResponse(status: 200, success: __('message.logout'));
        } catch (\Throwable $th) {
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    public function getAuthUserInfo(){
        try {
            $user = auth()->user()->only('id', 'role_id', 'first_name', 'last_name', 'email', 'phone', 'profile_image', 'status');
            if($user['role_id'] != 1){
                $user['permissions'] = Role::find($user['role_id'])->permissions->pluck('name');
                $record = Attendance::select('id', 'user_id', 'type', 'date', 'time', 'description', 'created_at')->where('user_id', $user['id'])->where('date', date('Y-m-d'))->first();

                if($record && !empty($record)){
                    $user['attendance'] = $record;
                }else{
                    $user['attendance'] = false;
                }
            }
            $user['role_name'] = Role::find($user['role_id'])->name;

            return jsonResponse(status: 200, data: $user);
        } catch (\Throwable $th) {
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Forgot Password
     *
     * @author Vishal Soni
     * @package Auth
     * @param Request $request
     * @return Json
     *
     */

    public function forgotPassword(Request $request){
        try {

            $rule = [
                "email" => 'required|email'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            $user_exist = User::where('email', $request->email)->where('status', 'active')->first();

            if(!$user_exist && empty($user_exist)){
                return jsonResponse(status: true, error: __('message.not_exists', ['User']));
            }

            $data = [
                'email' => $request->email,
                'token' => Str::random(60)
            ];

            $reset_url = getSettings('site_url').'reset-password?token=' . encrypt($data);

            Mail::send('emailTemplate.resetPassword', ['name' =>  $user_exist->first_name.' '.$user_exist->last_name,'url' => $reset_url], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject(env('APP_NAME') . ' | Reset Password');
            });
            $token_data = DB::table('password_reset_tokens')->where('email', $request->email)->first();

            if(!$token_data && empty($token_data)){
                DB::table('password_reset_tokens')->insert($data);
            }else{
                DB::table('password_reset_tokens')->update($data);
            }

            return jsonResponse(status: true, success: __('message.reset_password_sent'));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Reset Password
     *
     * @package AuthController
     * @author  Xipe Tech
     * @param   Request $request
     * @return  \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        try {
            $rule = [
                'password' => 'required|min:8|max:16',
                'password_confirmation' => 'required|same:password',
            ];
            if ($errors = isValidatorFails($request, $rule)) return $errors;
            DB::beginTransaction();

            $token = decrypt($request->token);
            $updatePassword = DB::table('password_reset_tokens')
                ->where([
                    'email' => $token['email'],
                    'token' => $token['token']
                ])
                ->first();

            if (!$updatePassword) {
                return jsonResponse(status:false, error: __('message.token_invalid'));
            }

            User::where('email', $token['email'])->update(['password' => Hash::make($request->password)]);
            DB::table('password_reset_tokens')->where(['email' => $token['email']])->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.reset_success'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }
}
