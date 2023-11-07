<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ContactDetail;
use App\Models\Notes;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    /**
     * Profile Update
     *
     * @author Vishal Soni
     * @package Admin
     * @param Request $request
     * @return Json
     *
     */

    public function updateAccountSetting(Request $request) {
        try {
            $rule = [
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'phone' => 'required|unique:users,phone,' . auth()->user()->id .',id'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            $user = User::find(auth()->user()->id);

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone = $request->phone;

            $user->save();


            $user = $user->only('id', 'first_name', 'last_name', 'email', 'phone', 'role_id', 'profile_image', 'status');
            $user['role_name'] = Role::find($user['role_id'])->name;
            return jsonResponse(status: true, data: $user, success: __('message.update', ['Personal Details']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Change Password
     *
     * @author Vishal Soni
     * @package Admin
     * @param Request $request
     * @return Json
     *
     */

    public function changePassword(Request $request){
        try {
            $rule = [
                'current_password' => 'required',
                'new_password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|same:confirm_password',
                'confirm_password' => 'required|min:8'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            $user  = User::select('password')->where('id', auth()->user()->id)->first();

            if(Hash::check($request->current_password, $user->password)){
                User::where('id', auth()->user()->id)->update(['password' => Hash::make($request->confirm_password)]);

                return jsonResponse(status: true, success: __('message.update', ['Password']));
            }else{
                return jsonResponse(status: false, error: __('message.password_not_match', ['Current Password']));
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Direct Login
     *
     * @package Admin
     * @author  Vishal Soni
     * @param   Request $request
     * @return  JsonResponse
     */

    public function directLogin(Request $request)
    {
        try {
            if ($request->id) {
                $user = User::find($request->id);
                if ($user) {
                    if ($user->status == 'active') {
                        $token = $user->createToken('auth_token')->plainTextToken;
                        $token = explode('|', $token);
                        $user->accessToken = $token[1];
                        $user = $user->only('id', 'first_name', 'last_name', 'email', 'phone', 'role_id', 'profile_image', 'status', 'accessToken');
                        $user['role_name'] = Role::find($user['role_id'])->name;
                        return jsonResponse(status: true, data: $user);
                    } else {
                        return jsonResponse(status: false, error: __('message.inactive_account', ['User']));
                    }
                } else {
                    return jsonResponse(status: false, error: __('message.not_exists', ['User']));
                }
            } else {
                return jsonResponse(status: false, error: __('message.error.500'));
            }
        } catch (\Throwable $th) {
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Create Contact Details
     *
     * @package Admin
     * @author  Vishal Soni
     * @param   Request $request
     * @return  JsonResponse
     *
     */

    public function createContactDetails(Request $request){
        try {
            $rule = [
                'name' => 'required',
                'email' => 'required|email|email:dns|unique:contact_details,email',
                'contact_no' => 'required|numeric|digits:10|unique:contact_details,contact_no',
                'alternate_contact_no' => 'sometimes|nullable|numeric|digits:10|unique:contact_details,alternate_contact_no',
                'industry' => 'required',
                'company' => 'required',
                'sales_non_sales' => 'required',
                'department' => 'required',
                'channel' => 'required',
                'state' => 'required',
                'location' => 'required',
                'address' => 'required'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $contact = new ContactDetail;

            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->contact_no = $request->contact_no;
            $contact->alternate_contact_no = $request->alternate_contact_no ?? null;
            $contact->industry_id = $request->industry;
            $contact->company_id = $request->company;
            $contact->sales_non_sales_id = $request->sales_non_sales;
            $contact->department_id = $request->department;
            $contact->channel_id = $request->channel;
            $contact->state_id = $request->state;
            $contact->location_id = $request->location;
            $contact->address = $request->address;
            $contact->reporting_manager_name = $request->reporting_manager_name ?? null;
            $contact->reporting_contact_no = $request->reporting_contact_no ?? null;
            $contact->reporting_email = $request->reporting_email ?? null;
            $contact->remark = $request->remark ?? null;

            $contact->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['Contact Detail']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Update Contact Details
     *
     * @package Admin
     * @author  Vishal Soni
     * @param   Request $request
     * @return  JsonResponse
     *
     */


    public function editContactDetails(Request $request){
        // try {

            $rule = [
                'name' => 'required',
                'email' => 'required|email|email:dns|unique:contact_details,email,' .$request->id. ',id',
                'contact_no' => 'required|numeric|digits:10|unique:contact_details,contact_no,' . $request->id . ',id',
                'alternate_contact_no' => 'sometimes|nullable|numeric|digits:10|unique:contact_details,alternate_contact_no,' . $request->id . ',id',
                'industry' => 'required',
                'company' => 'required',
                'sales_non_sales' => 'required',
                'department' => 'required',
                'channel' => 'required',
                'state' => 'required',
                'location' => 'required',
                'address' => 'required'
            ];


            if ($errors = isValidatorFails($request, $rule)) return $errors;

            $contact = ContactDetail::find($request->id);

            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->contact_no = $request->contact_no;
            $contact->alternate_contact_no = $request->alternate_contact_no ?? null;
            $contact->industry_id = $request->industry;
            $contact->company_id = $request->company;
            $contact->sales_non_sales_id = $request->sales_non_sales;
            $contact->department_id = $request->department;
            $contact->channel_id = $request->channel;
            $contact->state_id = $request->state;
            $contact->location_id = $request->location;
            $contact->address = $request->address;
            $contact->reporting_manager_name = $request->reporting_manager_name ?? null;
            $contact->reporting_contact_no = $request->reporting_contact_no ?? null;
            $contact->reporting_email = $request->reporting_email ?? null;
            $contact->remark = $request->remark ?? null;

            $contact->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Contact Detail']));
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     return catchResponse(method: __METHOD__, exception: $th);
        // }
    }

    /**
     * Delete Contact Details
     *
     * @package Admin
     * @author  Vishal Soni
     * @param   Request $request
     * @return  JsonResponse
     *
     */

    public function deleteContactDetails(Request $request){
        try {
            DB::beginTransaction();
            ContactDetail::find($request->id)->delete();
            DB::commit();
            return jsonResponse(status: true, success: __('message.delete', ['Contact Details']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * List Contact Details
     *
     * @package Admin
     * @author  Vishal Soni
     * @param   Request $request
     * @return  JsonResponse
     *
     */


    public function listContactDetails(){
        try {
            $data = ContactDetail::with(['stateName', 'industry', 'company', 'salesNon', 'department', 'channel', 'location']);

            return DataTables::of($data)
                            ->addIndexColumn()
                            ->editColumn('state_name', function($request){
                                $state =  json_decode($request->stateName, true);
                                unset($state['id']);
                                return $state;
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
                            ->editColumn('location', function($request){
                                $location =  json_decode($request->location, true);
                                unset($location['id']);
                                return $location;
                            })
                            ->escapeColumns([])
                            ->make(true);
        } catch (\Throwable $th) {
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Add Attendance
     *
     * @author Vishal Soni
     * @package AdminController
     * @param Request $request
     * @return JSON
     *
     */

    public function addAttendance(Request $request) {
        try {
            $rule = [
                'type' => 'required',
                'date' => 'required',
                'time' => 'required'
            ];

            if($request->type != 'attendance'){
                $rule = array_merge($rule, ['description' => 'required']);
            }

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $record = new Attendance;

            $record->user_id = auth()->user()->id;
            $record->date = $request->date;
            $record->time = $request->time;
            $record->type = $request->type;
            $record->description = $request->description ?? NULL;

            $record->save();
            DB::commit();

            return jsonResponse(status: true, success: __('message.create', [ucwords(str_replace('_', ' ', $request->type))]));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Edit Attendance
     *
     * @author Vishal Soni
     * @package AdminController
     * @param Request $request
     * @return JSON
     *
     */


    public function editAttendance(Request $request){
        try {

            $rule = [
                'type' => 'required',
                'date' => 'required',
                'time' => 'required'
            ];

            if($request->type != 'attendance'){
                $rule = array_merge($rule, ['description' => 'required']);
            }

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            DB::beginTransaction();

            $record = Attendance::find($request->id);

            $record->user_id = auth()->user()->id;
            $record->date = $request->date;
            $record->time = $request->time;
            $record->type = $request->type;
            $record->description = $request->description ?? NULL;

            $record->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', [ucwords(str_replace('_', ' ', $request->type))]));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


    /**
     * Delete Attendance
     * @author Vishal Soni
     * @package AdminController
     * @param Request $request
     * @return JSON
     *
     */


    public function deleteAttendance(Request $request){
        try {
            DB::beginTransaction();
            Attendance::find($request->id)->delete();
            DB::commit();

            return jsonResponse(status: true, success: __('message.delete', ['Record']));
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * List Attendance
     * @author Vishal Soni
     * @package AdminController
     * @param Request $request
     * @return JSON
     *
     */

    public function listAttendance(Request $request){
        try {
            $data = Attendance::select('attendances.id', 'attendances.user_id', 'attendances.type', 'attendances.date', 'attendances.time', 'attendances.description', 'attendances.created_at')->with(['userData']);
            if(isset($request->id) && $request->id){
               $data = $data->where('user_id', $request->id);
            }

            return DataTables::of($data)
                            ->addIndexColumn()
                            ->escapeColumns([])
                            ->make(true);
        } catch (\Throwable $th) {
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * List Attendance
     * @author Vishal Soni
     * @package AdminController
     * @param Request $request
     * @return JSON
     *
     */

    public function calenderList(Request $request){
        try {
            $data = Attendance::select('id AS event_id', 'user_id', 'type', 'type AS title', DB::raw("CONCAT(date,' ', time) AS event_timing"), DB::raw("CONCAT(date,' ', time) AS start"), 'description')
                                ->where('user_id', $request->id)
                                ->get();

            return jsonResponse(status: true, data: $data);
        } catch (\Throwable $th) {
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Add Notes
     * @author Vishal Soni
     * @package AdminController
     * @param Request $request
     * @return JSON
     *
     */

    public function addNotes(Request $request){
        try {
            $rule = [
                'company_name' => 'required',
                'subject' => 'required',
                'document' => 'required|mimes:docx,pdf,csv|max:2048'
            ];

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            if($request->hasFile('document')){
                $document = uploadFiles($request, 'document', 'notes');
            }

            DB::beginTransaction();

            $notes = new Notes;

            $notes->company_id = $request->company_name;
            $notes->subject = $request->subject;
            $notes->document = $document;
            $notes->remark = $request->remark;
            $notes->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.create', ['New Notes']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Edit Notes
     * @author Vishal Soni
     * @package AdminController
     * @param Request $request
     * @return JSON
     *
     */


    public function editNotes(Request $request){
        try {
            $rule = [
                'company_name' => 'required',
                'subject' => 'required'
            ];

            if($request->hasFile('document')){
                $rule = array_merge($rule, ['document' => 'mimes:docx,pdf,csv|max:2048']);
            }

            if ($errors = isValidatorFails($request, $rule)) return $errors;

            if($request->hasFile('document')){
                $document = uploadFiles($request, 'document', 'notes');
            }

            DB::beginTransaction();

            $notes = Notes::find($request->id);

            $notes->company_id = $request->company_name;
            $notes->subject = $request->subject;
            $notes->remark = $request->remark;
            if(isset($document)){
                $notes->document = $document;
            }
            $notes->save();

            DB::commit();

            return jsonResponse(status: true, success: __('message.update', ['Notes']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Delete Notes
     * @author Vishal Soni
     * @package AdminController
     * @param Request $request
     * @return JSON
     *
     */


    public function deleteNotes(Request $request){
        try {
            DB::beginTransaction();
            Notes::find($request->id)->delete();
            return jsonResponse(status: true, success: __('message.delete', ['Notes']));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }

    /**
     * Notes List
     * @author Vishal Soni
     * @package AdminController
     * @return JSON
     *
     */

    public function notesList(){
        try {
            $data = Notes::select('id', 'company_id', 'subject', 'document', 'remark')->with('companies');


            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('companies', function($request){
                        $company = json_decode($request->companies);
                        unset($company->id);
                        return $company;
                    })
                    ->addColumn('action', function($request) {
                        return $request->id;
                    })
                    ->escapeColumns([])
                    ->make(true);
        } catch (\Throwable $th) {
            return catchResponse(method: __METHOD__, exception: $th);
        }
    }


}
