<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Location;
use DataTables;
use DB;
class JobController extends Controller
{
    /**
     * Create a Job
     *
     * @author Vishal Soni
     * @package Job
     * @param Request $request
     * @return Json
     *
     */

    public function createJob(Request $request){
        try {
            $rule = [
                'hr_spoc' => 'required',
                'business_spoc' => 'required',
                'state' => 'required',
                'location' => 'required|array|min:1',
                'industry' => 'required',
                'company' => 'required',
                'sales_non_sales' => 'required',
                'department' => 'required',
                'channel' => 'required',
                'designation' => 'required',
                'level' => 'required',
                'product' => 'required',
                'openings' => 'required|gt:0',
                'ctc_from' => 'required',
                'ctc_to' => 'required',
                'status' => 'required',
                'job_description' => 'required'
            ];

            if($request->has('attach_job_description')){
                $rule = array_merge($rule, ['attach_job_description' => 'mimes:jpg,jpeg,png|max:3000']);
            }

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            if($request->has('attach_job_description')){
                $attached_jd = uploadFiles($request, 'attach_job_description', 'job_description');
            }

            DB::beginTransaction();

            $job = new Job;

            $job->user_id = auth()->user()->id;

            $job->hr_spoc = $request->hr_spoc;
            $job->business_spoc = $request->business_spoc;
            $job->state_id = $request->state;
            $job->location_id = json_encode($request->location);
            $job->industry_id = $request->industry;
            $job->company_id = $request->company;
            $job->sales_non_sales_id = $request->sales_non_sales;
            $job->department_id = $request->department;
            $job->channel_id = $request->channel;
            $job->designation_id = $request->designation;
            $job->level_id = $request->level;
            $job->product_id = $request->product;
            $job->openings = $request->openings;
            $job->ctc_from = $request->ctc_from;
            $job->ctc_to = $request->ctc_to;
            $job->status = $request->status;
            $job->job_description = $request->job_description;
            $job->attach_job_description = isset($attached_jd) ? $attached_jd : null;

            $job->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Job']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Job List
     *
     * @author Vishal Soni
     * @package Job
     * @param Request $request
     * @return Json
     *
     */

    public function jobList(){
        try {
            $data = Job::select('jobs.*')->with(['stateName', 'industry', 'salesNon', 'company', 'department', 'channel', 'level', 'product']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('state_name', function($request){
                    $state =  json_decode($request->stateName, true);
                    unset($state['id']);
                    return $state;
                })
                ->editColumn('location_id', function($request){
                   $location_data = Location::select('id AS value', 'name AS label')->whereIn('id', json_decode($request->location_id))->get()->toArray();
                   return $location_data;
                })
                ->editColumn('industry', function($request){
                    $industry =  json_decode($request->industry, true);
                    unset($industry['id']);
                    return $industry;
                })
                ->editColumn('company', function($request){
                    $company =  json_decode($request->company, true);
                    unset($company['id']);
                    return $company;
                })
                ->editColumn('department', function($request){
                    $department =  json_decode($request->department, true);
                    unset($department['id']);
                    return $department;
                })
                ->editColumn('channel', function($request){
                    $channel =  json_decode($request->channel, true);
                    unset($channel['id']);
                    return $channel;
                })
                ->editColumn('sales_non', function($request){
                    $channel =  json_decode($request->salesNon, true);
                    unset($channel['id']);
                    return $channel;
                })
                ->editColumn('level', function($request){
                    $level =  json_decode($request->level, true);
                    unset($level['id']);
                    return $level;
                })
                ->editColumn('product', function($request){
                    $product =  json_decode($request->product, true);
                    unset($product['id']);
                    return $product;
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
     * Edit a Job
     *
     * @author Vishal Soni
     * @package Job
     * @param Request $request
     * @return Json
     *
     */

    public function editJob(Request $request){
        try {
            $rule = [
                'hr_spoc' => 'required',
                'business_spoc' => 'required',
                'state' => 'required',
                'location' => 'required',
                'industry' => 'required',
                'company' => 'required',
                'sales_non_sales' => 'required',
                'department' => 'required',
                'channel' => 'required',
                'designation' => 'required',
                'level' => 'required',
                'product' => 'required',
                'openings' => 'required|gt:0',
                'ctc_from' => 'required',
                'ctc_to' => 'required',
                'status' => 'required',
                'job_description' => 'required'
            ];

            if($request->has('attach_job_description')){
                $rule = array_merge($rule, ['attach_job_description' => 'required|mimes:jpg,jpeg,png|max:3000']);
            }

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            if($request->has('attach_job_description')){
                $attached_jd = uploadFiles($request, 'attach_job_description', 'job_description');
            }

            DB::beginTransaction();

            $job = Job::find($request->id);

            $job->hr_spoc = $request->hr_spoc;
            $job->business_spoc = $request->business_spoc;
            $job->state_id = $request->state;
            $job->location_id = json_encode($request->location);
            $job->industry_id = $request->industry;
            $job->company_id = $request->company;
            $job->sales_non_sales_id = $request->sales_non_sales;
            $job->department_id = $request->department;
            $job->channel_id = $request->channel;
            $job->designation_id = $request->designation;
            $job->level_id = $request->level;
            $job->product_id = $request->product;
            $job->openings = $request->openings;
            $job->ctc_from = $request->ctc_from;
            $job->ctc_to = $request->ctc_to;
            $job->status = $request->status;
            $job->job_description = isset($request->job_description) ? $request->job_description : null;

            if(isset($attached_jd) && $attached_jd){
                if($attached_jd){
                    deleteFiles($job->attach_job_description);
                }
                $job->attach_job_description = $attached_jd;
            }

            $job->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Job']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Delete Job
     *
     * @author Vishal Soni
     * @package Job
     * @param Request $request
     * @return Json
     *
     */

    public function deleteJob(Request $request) {
        try {
            DB::beginTransaction();
            Job::find($request->id)->delete();
            DB::commit();
            return jsonResponse(status: true, success: __('message.delete', ['Job']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

}
