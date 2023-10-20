<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // try {
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

        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     return catchResponse(method: __METHOD__, exception: $th);
        // }
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
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }
}
