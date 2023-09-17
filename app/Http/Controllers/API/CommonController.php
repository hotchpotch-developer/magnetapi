<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use DB;

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
        // try {
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
        // } catch (\Throwable $th) {
        //     return catchResponse(method: __METHOD__, exception: $th);
        // }
    }
}
