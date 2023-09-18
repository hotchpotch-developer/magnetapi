<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\Department;
use App\Models\Industry;
use App\Models\Location;
use App\Models\CallingRemark;
use DB;
use DataTables;

class CommonController extends Controller
{
    /**
     * Common DropDown
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function commonDropDown(Request $request){
        try {
            if($request->type){
                Switch($request->type){
                    case ('permission'):

                    $data = Permission::select('name AS value', 'name AS label')->get();

                    break;

                    default:
                    $data = [];
                }

                return jsonResponse(status: true, data: $data);
            }
        } catch (\Throwable $th) {
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Add Department
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function addDepartment(Request $request) {
        try {
            $rule = [
                'department_name' => 'required|unique:departments,name'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $department = new Department;

            $department->name = $request->department_name;

            $department->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Department']));


        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Edit Department
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function editDepartment(Request $request) {
        try {

            $rule = [
                'department_name' => 'required|unique:departments,name,' . $request->id . ',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $department = Department::find($request->id);

            $department->name = $request->department_name;

            $department->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Department']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Delete Department
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function deleteDepartment(Request $request) {
        try {

            DB::beginTransaction();

            Department::find($request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Department']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Department List
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function departmentList(Request $request) {
        try {

            $data = Department::select('id', 'name');

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
     * Add Industry
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function addIndustry(Request $request) {
        try {
            $rule = [
                'industry_name' => 'required|unique:industries,name'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $industry = new Industry;

            $industry->name = $request->industry_name;

            $industry->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Industry']));


        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Edit Industry
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function editIndustry(Request $request) {
        try {

            $rule = [
                'industry_name' => 'required|unique:industries,name,' . $request->id . ',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $industry = Industry::find($request->id);

            $industry->name = $request->industry_name;

            $industry->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Industry']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Delete Industry
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function deleteIndustry(Request $request) {
        try {

            DB::beginTransaction();

            Industry::find($request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Industry']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Industry List
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function industryList(Request $request) {
        try {

            $data = Industry::select('id', 'name');

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
     * Add Location
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function addLocation(Request $request) {
        try {
            $rule = [
                'location_name' => 'required|unique:locations,name'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $location = new Location;

            $location->name = $request->location_name;

            $location->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Location']));


        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Edit Location
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function editLocation(Request $request) {
        try {

            $rule = [
                'location_name' => 'required|unique:locations,name,' . $request->id . ',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $location = Location::find($request->id);

            $location->name = $request->location_name;

            $location->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Location']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Delete Location
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function deleteLocation(Request $request) {
        try {

            DB::beginTransaction();

            Location::find($request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Location']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Location List
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function locationList(Request $request) {
        try {

            $data = Location::select('id', 'name');

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
     * Add Calling Remark
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function addRemark(Request $request) {
        try {
            $rule = [
                'remark' => 'required|unique:calling_remarks,remark'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $remark = new CallingRemark;

            $remark->remark = $request->remark;

            $remark->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Calling Remark']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Edit Remark
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function editRemark(Request $request) {
        try {

            $rule = [
                'remark' => 'required|unique:calling_remarks,remark,' . $request->id . ',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $remark = CallingRemark::find($request->id);

            $remark->remark = $request->remark;

            $remark->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Calling Remark']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Delete Location
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function deleteRemark(Request $request) {
        try {

            DB::beginTransaction();

            CallingRemark::find($request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Calling Remark']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Remark List
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

     public function remarkList(Request $request) {
        try {

            $data = CallingRemark::select('id', 'remark');

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

}
