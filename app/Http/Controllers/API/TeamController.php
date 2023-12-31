<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewTeamMember;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

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
                'phone' => 'required|unique:users,phone|numeric|digits:10',
                'email' => 'required|email|email:dns|unique:users,email',
                'role' => 'required',
                'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
                'status' => 'required',
                'alternet_email' => 'nullable|sometimes|email|email:dns|different:email|unique:user_metas,email_1',
                'alternet_phone' => 'nullable|sometimes|numeric|digits:10|different:phone|unique:user_metas,phone_1'
            ];

            if($request->has('profile_image')){
                $rule = array_merge($rule, ['profile_image' => 'required|max:3000|mimes:jpeg,png,jpg']);
            }

            if($request->has('proof_document')){
                $rule = array_merge($rule, ['proof_document' => 'required|max:3000|mimes:jpeg,png,jpg,pdf']);
            }

            $message = [
                'password.regex' => 'The :attribute is invalid. please write a password with special characters, characters and numbers.'
            ];

            if ($errors = isValidatorFails($request, $rule, $message)) return $errors;

            if($request->has('profile_image')){
                $profile_image = uploadFiles($request, 'profile_image', 'profile');
            }

            if($request->has('proof_document')){
                $proof_document = uploadFiles($request, 'proof_document', 'team_document');
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

            $user_meta = new UserMeta;

            $user_meta->user_id = $team->id;
            $user_meta->reporting_user_id = $request->reporting_user_id ? $request->reporting_user_id : 1;
            $user_meta->email_1 = $request->alternet_email;
            $user_meta->phone_1 = $request->alternet_phone;
            $user_meta->proof_document = $proof_document ?? null;
            $user_meta->additional_information = $request->additional_information ?? null;

            $user_meta->save();

            $mailData = [
                "name" => $request->first_name .' '. $request->last_name ,
                "email" => $request->email,
                "password" => $request->password,
                "company_name" => getSettings('site_name'),
                "designation" => Role::where('id', $request->role)->first()->name,
            ];

            Notification::route('mail', $request->email)->notify(new NewTeamMember($mailData));

            DB::commit();

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
                'phone' => 'required|unique:users,phone,' . $request->id .',id|numeric|digits:10',
                'email' => 'required|email|email:dns|unique:users,email,' . $request->id . ',id',
                'role' => 'required',
                'status' => 'required',
                'alternet_email' => 'nullable|sometimes|email|email:dns|different:email|unique:user_metas,email_1,' . $request->id . ',user_id',
                'alternet_phone' => 'nullable|sometimes|numeric|digits:10|different:phone|unique:user_metas,phone_1,' . $request->id . ',user_id'
            ];


            if($request->password){
                $rule = array_merge($rule, ['password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/']);
            }

            if($request->has('profile_image')){
                $rule = array_merge($rule, ['profile_image' => 'required|max:3000|mimes:jpeg,png,jpg']);
            }

            if($request->has('proof_document')){
                $rule = array_merge($rule, ['proof_document' => 'required|max:3000|mimes:jpeg,png,jpg,pdf']);
            }

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            if($request->has('profile_image')){
                $profile_image = uploadFiles($request, 'profile_image', 'profile');
            }

            DB::beginTransaction();

            $team = User::find($request->id);
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

            if($request->has('proof_document')){
                $proof_document = uploadFiles($request, 'proof_document', 'team_document');
            }

            $user_meta = UserMeta::where('user_id', $team->id)->first();

            $user_meta->reporting_user_id = $request->reporting_user_id ? $request->reporting_user_id : 1;
            $user_meta->email_1 = $request->alternet_email;
            $user_meta->phone_1 = $request->alternet_phone;
            $user_meta->additional_information = $request->additional_information ?? null;
            if(isset($proof_document) && $proof_document){
                if($user_meta->proof_document){
                    deleteFiles($user_meta->proof_document);
                }
                $team->proof_document = $proof_document;
            }

            $user_meta->save();

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
            $data = User::select('roles.id as roles_id', 'roles.name as roles_name', 'users.*', 'user_metas.reporting_user_id', 'user_metas.additional_information', 'user_metas.email_1', 'user_metas.phone_1', 'user_metas.proof_document')
                        ->join('roles', 'users.role_id', '=', 'roles.id')
                        ->leftJoin('user_metas', 'user_metas.user_id', '=', 'users.id')
                        ->where('users.role_id', '!=', 1);
                        if($request->type != 'all'){
                            $data = $data->where('name', $request->type);
                        }


                return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('permissions', function($request) {
                            return collect($request->getAllPermissions())->map(function ($item) { return ['value' => $item['name'],'label' => $item['name']]; })->toArray();
                        })
                        ->addColumn('reporting_user_name', function($request) {
                            $user = User::select('first_name','last_name')->where('id', $request->reporting_user_id)->first();
                            return json_decode($user);
                        })
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
            UserMeta::where('user_id', $request->id)->delete();
            User::where('id', $request->id)->delete();
            DB::commit();

            return jsonResponse(status: true, success: __('message.team.delete'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


}
