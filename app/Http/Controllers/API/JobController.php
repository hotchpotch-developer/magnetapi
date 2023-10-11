<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
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
                'state_id' => 'required',
                'location_id' => 'required',
                'industry_id' => 'required',
                'company_id' => 'required',
                'sales_non_sales' => 'required',
                'department_id' => 'required',
                'channel_id' => 'required',
                'designation_id' => 'required',
                'level_id' => 'required',
                'product_id' => 'required',
                'openings' => 'required|gt:0',
                'ctc_from' => 'required',
                'ctc_to' => 'required',
                'job_jd' => 'required',
                'status' => 'required'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $job = new Job;

            $job->user_id = auth()->user()->id;
            $job->position_no = 'sfsf';
            $job->hr_spoc = $request->hr_spoc;
            $job->business_spoc = $request->business_spoc;
            $job->state_id = $request->state_id;
            $job->location_id = $request->location_id;
            $job->industry_id = $request->industry_id;
            $job->company_id = $request->company_id;
            $job->sales_non_sales = $request->sales_non_sales;
            $job->department_id = $request->department_id;
            $job->channel_id = $request->channel_id;
            $job->designation_id = $request->designation_id;
            $job->level_id = $request->level_id;
            $job->product_id = $request->product_id;
            $job->openings = $request->openings;
            $job->ctc_from = $request->ctc_from;
            $job->ctc_to = $request->ctc_to;
            $job->status = $request->status;
            $job->job_jd = $request->job_jd;

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
            $data = Job::select('jobs.*')->with(['stateName','location', 'company', 'designation', 'department']);

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


}
