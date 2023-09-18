<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

if(!function_exists('jsonResponse')){

    function jsonResponse($status, $data = '', $success = '', $error = '', $errors = '') {

        return response()->json(["status" => $status, "data" => $data, "success" => $success, "error" => $error, "errors" => $errors]);      

    }

}

if(!function_exists('catchResponse')) {
    function catchResponse($method, $exception){
        \Log::error($method . ' Please check issue on line no '. $exception->getLine() .', error is '. $exception->getMessage());
        return jsonResponse(500, '',__('message.error.500'));
    }
}

if (!function_exists('isValidatorFails')) {
    function isValidatorFails(Request $request, $rule, $message = [])
    {
        $validator = Validator::make($request->all(), $rule, $message);

        return $validator->fails() ? jsonResponse(status: false, errors: $validator->errors()) : false;
    }
}


 if (!function_exists('uploadFiles')) {
    function uploadFiles(Request $request, $param, $folder)
    {
        $imageNameArr = [];
        if ($request->hasFile($param)) {
            if (is_array($request->file($param))) {
                foreach ($request->file($param) as $file) {
                    $imageName = Storage::disk('public')->putFile($folder, $file);
                    array_push($imageNameArr, $imageName);
                }
            } else {
                $imageName = Storage::disk('public')->putFile($folder, $request->file($param));
                array_push($imageNameArr, $imageName);
            }
        }
        return implode(',', $imageNameArr);
    }
}

if (!function_exists('deleteFiles')) {
    function deleteFiles($fileName)
    {
        try {
            $fileNameArr = explode(',', $fileName);

            foreach ($fileNameArr as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        } catch (\Exception $e) {
            return returnResponse(error: 'Unable to delete files.');
        }
    }
}

?>