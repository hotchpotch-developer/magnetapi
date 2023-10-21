<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CandidateController extends Controller
{
    /**
     * Create Candidate
     *
     * @author Vishal Soni
     * @package CandidateController
     * @param Request $request
     * @return Json
     *
     */

    public function createCandidate(Request $request){
        try {
            $rule = [
                'first_name' => 'required|min:3',
                'last_name' => 'required|min:3',
                'mobile' => 'required|numeric|min:10|unique:candidates,mobile',
                'email' => 'required|email|unique:candidates,email',
                'gender' => 'required',
                'date_of_birth' => 'required',
                'high_qualification' => 'required',
                'state' => 'required',
                'location' => 'required',
                'industry' => 'required',
                'company' => 'required',
                'sales_non_sales' => 'required',
                'department' => 'required',
                'channel' => 'required',
                'designation' => 'required',
                'level' => 'required',
                'experience' => 'required|numeric',
                'current_ctc' => 'required|numeric',
                'pan_no' => 'required',
                'employment_status' => 'required'
            ];

            if($request->resume_status == 'attached'){
                $rule = array_merge($rule, ['resume_file' => 'required|max:3000|mimes:jpeg,png,jpg,pdf,docx']);
            }

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            if($request->resume_status == 'attached' && $request->has('resume_file')){
                $attached_resume = uploadFiles($request, 'resume_file', 'resume_file');
            }

            $candidate = new Candidate;

            $candidate->created_by = auth()->user()->id;
            $candidate->first_name = strtoupper($request->first_name);
            $candidate->last_name = strtoupper($request->last_name);
            $candidate->email = strtoupper($request->email);
            $candidate->mobile = $request->mobile;
            $candidate->alternate_mobile = $request->alternate_mobile;
            $candidate->alternate_email = strtoupper($request->alternate_email);
            $candidate->gender = strtoupper($request->gender);
            $candidate->dob = $request->date_of_birth;
            $candidate->high_qualification_id = $request->high_qualification;
            $candidate->state_id = $request->state;
            $candidate->location_id = $request->location;
            $candidate->industry_id = $request->industry;
            $candidate->company_id = $request->company;
            $candidate->sales_non_sales_id = $request->sales_non_sales;
            $candidate->department_id = $request->department;
            $candidate->channel_id = $request->channel;
            $candidate->designation = strtoupper($request->designation);
            $candidate->level_id = $request->level;
            $candidate->experience = $request->experience;
            $candidate->current_ctc = $request->current_ctc;
            $candidate->pan_no = $request->pan_no;
            $candidate->employment_status = strtoupper($request->employment_status);
            $candidate->resume_status = $request->resume_status;
            $candidate->resume_file = isset($attached_resume) && $attached_resume ? $attached_resume : null;

            $candidate->save();

            return jsonResponse(status: true, success: __('message.create', ['Candidate']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Edit Candidate
     *
     * @author Vishal Soni
     * @package CandidateController
     * @param Request $request
     * @return Json
     *
     */

    public function editCandidate(Request $request){
        try {

            $rule = [
                'first_name' => 'required|min:3',
                'last_name' => 'required|min:3',
                'gender' => 'required',
                'date_of_birth' => 'required',
                'high_qualification' => 'required',
                'state' => 'required',
                'location' => 'required',
                'industry' => 'required',
                'company' => 'required',
                'sales_non_sales' => 'required',
                'department' => 'required',
                'channel' => 'required',
                'designation' => 'required',
                'level' => 'required',
                'experience' => 'required|numeric',
                'current_ctc' => 'required|numeric',
                'pan_no' => 'required',
                'employment_status' => 'required'
            ];

            if($request->hasFile('resume_file') && $request->resume_status == 'attached'){
                $rule = array_merge($rule, ['resume_file' => 'required|max:3000|mimes:jpeg,png,jpg,pdf,docx']);
            }

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            if($request->hasFile('resume_file') && $request->resume_status == 'attached'){
                $attached_resume = uploadFiles($request, 'resume_file', 'resume_file');
            }

            DB::beginTransaction();

            $candidate = Candidate::find($request->id);

            $candidate->first_name = strtoupper($request->first_name);
            $candidate->last_name = strtoupper($request->last_name);
            $candidate->email = strtoupper($request->email);
            $candidate->mobile = $request->mobile;
            $candidate->alternate_mobile = $request->alternate_mobile;
            $candidate->alternate_email = strtoupper($request->alternate_email);
            $candidate->gender = strtoupper($request->gender);
            $candidate->dob = $request->date_of_birth;
            $candidate->high_qualification_id = $request->high_qualification;
            $candidate->state_id = $request->state;
            $candidate->location_id = $request->location;
            $candidate->industry_id = $request->industry;
            $candidate->company_id = $request->company;
            $candidate->sales_non_sales_id = $request->sales_non_sales;
            $candidate->department_id = $request->department;
            $candidate->channel_id = $request->channel;
            $candidate->designation = strtoupper($request->designation);
            $candidate->level_id = $request->level;
            $candidate->experience = $request->experience;
            $candidate->current_ctc = $request->current_ctc;
            $candidate->pan_no = $request->pan_no;
            $candidate->employment_status = strtoupper($request->employment_status);
            $candidate->resume_status = $request->resume_status;
            $candidate->resume_file = isset($attached_resume) && $attached_resume ? $attached_resume : null;

            $candidate->save();
            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Candidate']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Candidate List
     *
     * @author Vishal Soni
     * @package CandidateController
     * @param Request $request
     * @return Json
     *
     */

    public function candidateList(){
        try {

            $data = Candidate::select('*')->with(['stateName', 'location', 'industry', 'salesNon', 'company', 'department', 'channel', 'level', 'qualification'])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('state_name', function($request){
                    $state =  json_decode($request->stateName, true);
                    unset($state['id']);
                    return $state;
                })
                ->editColumn('location', function($request){
                    $location =  json_decode($request->location, true);
                    unset($location['id']);
                    return $location;
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
     * Delete Candidate
     *
     * @author Vishal Soni
     * @package CandidateController
     * @param Request $request
     * @return Json
     *
     */


    public function deleteCandidate(Request $request){
        try {
            DB::beginTransaction();

            Candidate::find($request->id)->delete();

            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Candidate']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }
}
