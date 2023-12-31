<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\API\CandidateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::group(['middleware' => ['auth:sanctum']], function() {

    #Get User Info
    Route::get('get-auth-user-info', [AuthController::class, 'getAuthUserInfo']);

    #Contact Details
    Route::post('create-contact-details', [AdminController::class, 'createContactDetails']);
    Route::post('edit-contact-details', [AdminController::class, 'editContactDetails']);
    Route::get('delete-contact-details/{id}', [AdminController::class, 'deleteContactDetails']);
    Route::get('list-contact-details', [AdminController::class, 'listContactDetails']);

    #Account Settings
    Route::post('update-account-setting', [AdminController::class, 'updateAccountSetting']);
    Route::post('change-password', [AdminController::class, 'changePassword']);
    Route::get('direct-login', [AdminController::class, 'directLogin']);

    #Common Entry
    Route::get('common-dropdown', [CommonController::class, 'commonDropDown']);

    Route::post('add-department', [CommonController::class, 'addDepartment']);
    Route::post('edit-department', [CommonController::class, 'editDepartment']);
    Route::get('delete-department/{id}', [CommonController::class, 'deleteDepartment']);
    Route::get('department-list', [CommonController::class, 'departmentList']);

    Route::post('add-industry', [CommonController::class, 'addIndustry']);
    Route::post('edit-industry', [CommonController::class, 'editIndustry']);
    Route::get('delete-industry/{id}', [CommonController::class, 'deleteIndustry']);
    Route::get('industry-list', [CommonController::class, 'industryList']);

    Route::post('add-location', [CommonController::class, 'addLocation']);
    Route::post('edit-location', [CommonController::class, 'editLocation']);
    Route::get('delete-location/{id}', [CommonController::class, 'deleteLocation']);
    Route::get('location-list', [CommonController::class, 'locationList']);

    Route::post('add-remark', [CommonController::class, 'addRemark']);
    Route::post('edit-remark', [CommonController::class, 'editRemark']);
    Route::get('delete-remark/{id}', [CommonController::class, 'deleteRemark']);
    Route::get('remark-list', [CommonController::class, 'remarkList']);

    Route::post('add-source', [CommonController::class, 'addSource']);
    Route::post('edit-source', [CommonController::class, 'editSource']);
    Route::get('delete-source/{id}', [CommonController::class, 'deleteSource']);
    Route::get('source-list', [CommonController::class, 'sourceList']);

    Route::post('add-company', [CommonController::class, 'addCompany']);
    Route::post('edit-company', [CommonController::class, 'editCompany']);
    Route::get('delete-company/{id}', [CommonController::class, 'deleteCompany']);
    Route::get('company-list', [CommonController::class, 'companyList']);

    Route::post('add-channel', [CommonController::class, 'addChannel']);
    Route::post('edit-channel', [CommonController::class, 'editChannel']);
    Route::get('delete-channel/{id}', [CommonController::class, 'deleteChannel']);
    Route::get('channel-list', [CommonController::class, 'channelList']);

    Route::post('add-designation', [CommonController::class, 'addDesignation']);
    Route::post('edit-designation', [CommonController::class, 'editDesignation']);
    Route::get('delete-designation/{id}', [CommonController::class, 'deleteDesignation']);
    Route::get('designation-list', [CommonController::class, 'designationList']);

    Route::post('add-product', [CommonController::class, 'addProduct']);
    Route::post('edit-product', [CommonController::class, 'editProduct']);
    Route::get('delete-product/{id}', [CommonController::class, 'deleteProduct']);
    Route::get('product-list', [CommonController::class, 'productList']);

    Route::post('add-level', [CommonController::class, 'addLevel']);
    Route::post('edit-level', [CommonController::class, 'editLevel']);
    Route::get('delete-level/{id}', [CommonController::class, 'deleteLevel']);
    Route::get('level-list', [CommonController::class, 'levelList']);

    Route::post('add-state', [CommonController::class, 'addState']);
    Route::post('edit-state', [CommonController::class, 'editState']);
    Route::get('delete-state/{id}', [CommonController::class, 'deleteState']);
    Route::get('state-list', [CommonController::class, 'stateList']);

    Route::post('add-sales-non-sales', [CommonController::class, 'addSalesNonSales']);
    Route::post('edit-sales-non-sales', [CommonController::class, 'editSalesNonSales']);
    Route::get('delete-sales-non-sales/{id}', [CommonController::class, 'deleteSalesNonSales']);
    Route::get('sales-non-sales-list', [CommonController::class, 'salesNonSalesList']);

    Route::post('add-qualification', [CommonController::class, 'addQualification']);
    Route::post('edit-qualification', [CommonController::class, 'editQualification']);
    Route::get('delete-qualification/{id}', [CommonController::class, 'deleteQualification']);
    Route::get('qualification-list', [CommonController::class, 'qualificationList']);


    #Auth
    Route::get('logout', [AuthController::class, 'logout']);

    #Permission
    Route::post('create-role', [PermissionController::class, 'createRole']);
    Route::post('edit-role', [PermissionController::class, 'editRole']);
    Route::get('delete-role/{id}', [PermissionController::class, 'deleteRole']);
    Route::get('role-list', [PermissionController::class, 'roleList']);

    Route::get('permission-list', [PermissionController::class, 'permissionList']);
    Route::post('create-permission', [PermissionController::class, 'createPermission']);
    Route::post('edit-permission', [PermissionController::class, 'editPermission']);
    Route::get('delete-permission/{id}', [PermissionController::class, 'deletePermission']);

    Route::post('assign-permission', [PermissionController::class, 'assignPermission']);

    #Teams
    Route::post('create-team', [TeamController::class, 'createTeam']);
    Route::post('edit-team', [TeamController::class, 'editTeam']);
    Route::get('team-list', [TeamController::class, 'teamList']);
    Route::get('delete-team/{id}', [TeamController::class, 'deleteTeam']);

    #Job
    Route::post('create-job', [JobController::class, 'createJob']);
    Route::post('edit-job', [JobController::class, 'editJob']);
    Route::get('delete-job/{id}', [JobController::class, 'deleteJob']);
    Route::get('job-list', [JobController::class, 'jobList']);

    #Candidate
    Route::post('create-candidate', [CandidateController::class, 'createCandidate']);
    Route::post('edit-candidate', [CandidateController::class, 'editCandidate']);
    Route::get('candidate-list', [CandidateController::class, 'candidateList']);
    Route::get('delete-candidate/{id}', [CandidateController::class, 'deleteCandidate']);


    #Attendance
    Route::post('add-attendance', [AdminController::class, 'addAttendance']);
    Route::post('edit-attendance', [AdminController::class, 'editAttendance']);
    Route::get('delete-attendance/{id}', [AdminController::class, 'deleteAttendance']);
    Route::get('list-attendance/{id?}', [AdminController::class, 'listAttendance']);
    Route::get('calendar-list/{id}', [AdminController::class, 'calenderList']);

    #Notes
    Route::post('add-notes', [AdminController::class, 'addNotes']);
    Route::post('edit-notes', [AdminController::class, 'editNotes']);
    Route::get('delete-notes/{id}', [AdminController::class, 'deleteNotes']);
    Route::get('notes-list', [AdminController::class, 'notesList']);

});
