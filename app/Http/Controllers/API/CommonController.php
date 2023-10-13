<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Department;
use App\Models\Industry;
use App\Models\Location;
use App\Models\CallingRemark;
use App\Models\CandidateSource;
use App\Models\Company;
use App\Models\Channel;
use App\Models\Designation;
use App\Models\Product;
use App\Models\Level;
use App\Models\State;
use App\Models\SalesNonSales;
use App\Models\User;
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

                    case('roles'):
                    $data = Role::select('id AS value', 'name AS label')->where('id', '!=', 1)->get();
                    break;

                    case('reporting_user'):
                    $data = User::select('id AS value', DB::raw("CONCAT(users.first_name,' ',users.last_name) AS label"))->where('role_id', '!=', 1)->where('role_id', '!=', 5)->get();
                    break;

                    case('department'):
                    $data = Department::select('id AS value',  'name AS label')->get();
                    break;

                    case('location'):
                    $data = Location::select('id AS value',  'name AS label')->get();
                    break;

                    case('industry'):
                    $data = Industry::select('id AS value',  'name AS label')->get();
                    break;

                    case('sales_no_sales'):
                    $data = SalesNonSales::select('id AS value',  'name AS label')->get();
                    break;

                    case('state'):
                    $data = State::select('id AS value',  'name AS label')->get();
                    break;

                    case('company'):
                    $data = Company::select('id AS value',  'name AS label')->get();
                    break;

                    case('channel'):
                    $data = Channel::select('id AS value',  'name AS label')->get();
                    break;

                    case('level'):
                    $data = Level::select('id AS value',  'name AS label')->get();
                    break;

                    case('product'):
                    $data = Product::select('id AS value',  'name AS label')->get();
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
     * Edit Calling Remark
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
     * Delete Calling Remark
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
     * Calling Remark List
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


    /**
     * Add Candidate Source
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function addSource(Request $request) {
        try {
            $rule = [
                'source' => 'required',
                'source_name' => 'required||unique:candidate_sources,source_name'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $source = new CandidateSource;

            $source->source = $request->source;
            $source->source_name = $request->source_name;

            $source->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Candidate Source']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Edit Candidate Source
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function editSource(Request $request) {
        try {
            $rule = [
                'source' => 'required',
                'source_name' => 'required||unique:candidate_sources,source_name,' . $request->id . ',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $source = CandidateSource::find($request->id);

            $source->source = $request->source;
            $source->source_name = $request->source_name;

            $source->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Candidate Source']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Delete Candidate Source
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function deleteSource(Request $request) {
        try {

            DB::beginTransaction();

            CandidateSource::find($request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Candidate Source']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Candidate Source List
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function sourceList(Request $request) {
        try {

            $data = CandidateSource::select('id', 'source', 'source_name');

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
     * Add Company
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function addCompany(Request $request) {
        try {
            $rule = [
                'company_name' => 'required|unique:companies,name'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $company = new Company;

            $company->name = $request->company_name;

            $company->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Company']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Edit Company
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function editCompany(Request $request) {
        try {
            $rule = [
                'company_name' => 'required|unique:companies,name,'. $request->id .',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $company = Company::find($request->id);

            $company->name = $request->company_name;

            $company->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Company']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * delete Company
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function deleteCompany(Request $request) {
        try {

            DB::beginTransaction();

            Company::find($request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Company']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Candidate Source List
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function companyList(Request $request) {
        try {

            $data = Company::select('id', 'name');

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
     * Add Channel
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function addChannel(Request $request) {
        try {
            $rule = [
                'channel_name' => 'required|unique:channels,name'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $channel = new Channel;

            $channel->name = $request->channel_name;

            $channel->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Channel']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Edit Channel
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function editChannel(Request $request) {
        try {
            $rule = [
                'channel_name' => 'required|unique:channels,name,'. $request->id .',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $channel = Channel::find($request->id);

            $channel->name = $request->channel_name;

            $channel->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Channel']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Delete Channel
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function deleteChannel(Request $request) {
        try {

            DB::beginTransaction();

            Channel::find($request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Channel']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Channel List
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function channelList(Request $request) {
        try {

            $data = Channel::select('id', 'name');

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
     * Add Designation
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function addDesignation(Request $request) {
        try {
            $rule = [
                'designation_name' => 'required|unique:designations,name'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $designation = new Designation;

            $designation->name = $request->designation_name;

            $designation->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Designation']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Edit Designation
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function editDesignation(Request $request) {
        try {
            $rule = [
                'designation_name' => 'required|unique:designations,name,'. $request->id .',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $designation = Designation::find($request->id);

            $designation->name = $request->designation_name;

            $designation->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Designation']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Delete Designation
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function deleteDesignation(Request $request) {
        try {

            DB::beginTransaction();

            Designation::find($request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Designation']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Designation List
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function designationList(Request $request) {
        try {

            $data = Designation::select('id', 'name');

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
     * Add Product
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function addProduct(Request $request) {
        try {
            $rule = [
                'product_name' => 'required|unique:products,name'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $product = new Product;

            $product->name = $request->product_name;

            $product->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Product']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Edit Product
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function editProduct(Request $request) {
        try {
            $rule = [
                'product_name' => 'required|unique:products,name,'. $request->id .',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $product = Product::find($request->id);

            $product->name = $request->product_name;

            $product->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Product']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Delete Product
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function deleteProduct(Request $request) {
        try {

            DB::beginTransaction();

            Product::find($request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Product']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Products List
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function productList(Request $request) {
        try {

            $data = Product::select('id', 'name');

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
     * Add Level
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function addLevel(Request $request) {
        try {
            $rule = [
                'level_name' => 'required|unique:levels,name'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $level = new Level;

            $level->name = $request->level_name;

            $level->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Level']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Edit Level
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function editLevel(Request $request) {
        try {
            $rule = [
                'level_name' => 'required|unique:levels,name,'. $request->id .',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $level = Level::find($request->id);

            $level->name = $request->level_name;

            $level->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Level']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Delete Level
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function deleteLevel(Request $request) {
        try {

            DB::beginTransaction();

            Level::find($request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Level']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Products List
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function levelList(Request $request) {
        try {

            $data = Level::select('id', 'name');

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
     * Add State
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function addState(Request $request) {
        try {

            $rule = [
                'state_name' => 'required|unique:states,name'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $state = new State;

            $state->name = $request->state_name;

            $state->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['State']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Edit State
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function editState(Request $request) {
        try {
            $rule = [
                'state_name' => 'required|unique:states,name,'. $request->id .',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $state = State::find($request->id);

            $state->name = $request->state_name;

            $state->save();
            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['State']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Delete State
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function deleteState(Request $request) {
        try {
            DB::beginTransaction();

            State::find($request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['State']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Products List
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function stateList(Request $request) {
        try {

            $data = State::select('id', 'name');

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
     * Add Sales/Non-Sales
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function addSalesNonSales(Request $request) {
        try {

            $rule = [
                'name' => 'required|unique:sales_non_sales,name'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $sales = new SalesNonSales;

            $sales->name = $request->name;

            $sales->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Sales/Non-Sales']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Edit Sales/Non-Sales
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function editSalesNonSales(Request $request) {
        try {
            $rule = [
                'name' => 'required|unique:sales_non_sales,name,'. $request->id .',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $sales = SalesNonSales::find($request->id);

            $sales->name = $request->name;

            $sales->save();
            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Sales/Non-Sales']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Delete Sales/Non-Sales
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function deleteSalesNonSales(Request $request) {
        try {
            DB::beginTransaction();

            SalesNonSales::find($request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Sales/Non-Sales']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Sales/Non-Sales List
     * 
     * @author Vishal Soni
     * @package Common
     * @param Request $request
     * @return JSON
     */

    public function salesNonSalesList(Request $request) {
        try {

            $data = SalesNonSales::select('id', 'name');

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
