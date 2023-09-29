<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewTeamMember;
use App\Models\User;
use DataTables;
use DB;

class TeamController extends Controller
{
    /**
     * Create Team Member
     * 
     * @author Vishal Soni
     * @package Team
     * @param Request $request
     * @return JSON
     */

    public function createTeam(Request $request) {
        try {
            $rule = [
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'phone' => 'required|unique:users,phone',
                'email' => 'required|unique:users,email',
                'role' => 'required',
                'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'status' => 'required',
                'employee_id' => 'required|unique:users,emp_id'
            ];

            if($request->has('profile_image')){
                $rule = array_merge($rule, ['profile_image' => 'required|max:3000|mimes:jpeg,png,jpg']);
            }

            $message = [
                'password.regex' => 'The :attribute is invalid. please write a password with special characters, characters and numbers.'
            ];

            if ($errors = isValidatorFails($request, $rule, $message)) return $errors;

            if($request->has('profile_image')){
                $profile_image = uploadFiles($request, 'profile_image', 'profile');
            }

            DB::beginTransaction();

            $team = new User;

            $team->emp_id = $request->employee_id;
            $team->first_name = $request->first_name;
            $team->last_name = $request->last_name;
            $team->phone = $request->phone;
            $team->email = $request->email;
            $team->role_id = $request->role;
            $team->status = $request->status;
            $team->password = Hash::make($request->password);
            $team->profile_image = isset($profile_image) ? $profile_image : NULL;

            $team->save();

            DB::commit();

            $mailData = [
                "name" => $request->first_name,
                "email" => $request->email,
                "password" => $request->password,
                "company_name" => getSettings('site_name'),
                "designation" => Role::where('id', $request->role)->first()->name,
            ];

            // Notification::route('mail', $request->email)->notify(new NewTeamMember($mailData));

            return jsonResponse(status: true, success: __('message.team.create'));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Edit Team Member
     * 
     * @author Vishal Soni
     * @package Team
     * @param Request $request
     * @return JSON
     */

    public function editTeam(Request $request) {
        try {
            
            $rule = [
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'role' => 'required',
                'status' => 'required',
                'employee_id' => 'required|unique:users,emp_id,' . $request->id .',id'
            ];
            

            if($request->password){
                $rule = array_merge($rule, ['password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/']);
            }

            if($request->has('profile_image')){
                $rule = array_merge($rule, ['profile_image' => 'required|max:3000|mimes:jpeg,png,jpg']);
            }

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            if($request->has('profile_image')){
                $profile_image = uploadFiles($request, 'profile_image', 'profile');
            }

            DB::beginTransaction();

            $team = User::find($request->id);

            $team->emp_id = $request->employee_id;
            $team->first_name = $request->first_name;
            $team->last_name = $request->last_name;
            $team->role_id = $request->role;
            $team->status = $request->status;

            if($request->password) {
                $team->password = Hash::make($request->password);
            }

            if(isset($profile_image) && $profile_image){
                if($team->profile_image){
                    deleteFiles($team->profile_image);
                }

                $team->profile_image = $profile_image;
            }

            $team->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.team.update'));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * List Team Member
     * 
     * @author Vishal Soni
     * @package Team
     * @param Request $request
     * @return JSON
     */

    public function teamList(Request $request){
        try {
            $data = Role::select('roles.id as roles_id', 'roles.name as roles_name', 'users.*')
                        ->join('users', 'users.role_id', '=', 'roles.id')
                        ->where('name', $request->type);
                    
                return DataTables::of($data)
                        ->addIndexColumn()
                        ->editColumn('action', function ($request) {
                            return $request->id;
                        })
                        ->escapeColumns([])
                        ->make(true);

        } catch (\Throwable $th) {
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Delete Team Member
     * 
     * @author Vishal Soni
     * @package Team
     * @param Request $request
     * @return JSON
     */
    
    public function deleteTeam(Request $request) {
        try {
            DB::beginTransaction();
            User::where('id', $request->id)->delete();
            DB::commit();

            return jsonResponse(status: true, success: __('message.team.delete'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    
}
