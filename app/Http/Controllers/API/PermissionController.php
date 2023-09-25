<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables;
use DB;

class PermissionController extends Controller
{
    /**
     * Create Role
     * 
     * @author Vishal Soni  
     * @package Permission
     * @param Request $request
     * @return JSON
     */

    public function createRole(Request $request) {
        try {
            $rule = [
                'role_name' => 'required|unique:roles,name'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();
            $role = new Role;

            $role->name = $request->role_name;
            $role->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Role']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Edit Role
     * 
     * @author Vishal Soni  
     * @package Permission
     * @param Request $request
     * @return JSON
     */

    public function editRole(Request $request) {
        try {
            $rule = [
                'role_name' => 'required|unique:roles,name,'. $request->id . ',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();
            
            $role = Role::where('id', $request->id)->first();

            $role->name = $request->role_name;
            $role->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Role']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Delete Role
     * 
     * @author Vishal Soni  
     * @package Permission
     * @param Request $request
     * @return JSON
     */

    public function deleteRole(Request $request) {
        try {

            DB::beginTransaction();

            Role::where('id', $request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Role']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Role List
     * 
     * @author Vishal Soni  
     * @package Permission
     * @param Request $request
     * @return JSON
     */

    public function roleList(Request $request) {
        try {

            $data = Role::select('id', 'name')->where('id', '!=', 1);

            return DataTables::of($data)
                        ->addIndexColumn()
                        ->editColumn('action', function ($request) {
                            return $request->id;
                        })
                        ->escapeColumns([])
                        ->make(true);

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Permission List
     * 
     * @author Vishal Soni  
     * @package Permission
     * @param Request $request
     * @return JSON
     */

    public function permissionList(Request $request) {
        try {

            $data = Permission::select('id', 'name');

            return DataTables::of($data)
                        ->addIndexColumn()
                        ->editColumn('action', function ($request) {
                            return $request->id;
                        })
                        ->escapeColumns([])
                        ->make(true);

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Create Permission
     * 
     * @author Vishal Soni  
     * @package Permission
     * @param Request $request
     * @return JSON
     */

    public function createPermission(Request $request) {
        try {
            $rule = [
                'permission_name' => 'required|unique:permissions,name'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();
            $permission = new Permission;

            $permission->name = $request->permission_name;
            $permission->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Permission']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Edit Permission
     * 
     * @author Vishal Soni  
     * @package Permission
     * @param Request $request
     * @return JSON
     */

    public function editPermission(Request $request) {
        try {
            $rule = [
                'permission_name' => 'required|unique:permissions,name,'. $request->id .',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();
            $permission = Permission::where('id', $request->id)->first();

            $permission->name = $request->permission_name;
            $permission->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Permission']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Delete Permission
     * 
     * @author Vishal Soni  
     * @package Permission
     * @param Request $request
     * @return JSON
     */

    public function deletePermission(Request $request) {
        try {

            DB::beginTransaction();

            Permission::where('id', $request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Permission']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Assign Permission
     * 
     * @author Vishal Soni
     * @package Permission
     * @param Request $request
     * @return JSON
     */

    public function assignPermission(Request $request) {
        try {

            $rule = [
                "role_id" => "required",
                "permission_name" => "required"
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $role = Role::where('id', $request->role_id)->first();

            $role->givePermissionTo($request->permission_name);

            DB::commit();

            return  jsonResponse(status: true, success: __('message.assign_permission_success'));


        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }
}
