<?php

/**
 * Write code on Method
 *
 * @return response()
 */

use App\Utilities\Constants;

if (!function_exists('sendSuccess')) {
    function sendSuccess($message, $data = null)
    {
        return response()->json(
            [
                'status' => true,
                'statusCode' => 200,
                'message' => $message,
                'data' => $data,
            ],
            200,
        );
    }
}

if (!function_exists('sendError')) {
    function sendError($message, $data = null,  $error = null)
    {
        return response()->json(
            [
                'status' => false,
                'statusCode' => 400,
                'message' => $message,
                'data' => $data,
                'error' => $error,
            ],
            200,
        );
    }
}

if (!function_exists('customValidationError')) {
    function customValidationError($errors = null)
    {
        return response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $errors,
        ]);
    }
}

if (!function_exists('returnToApi')) {
    function returnToApi($type, $message = null, $return_object = null)
    {
        if ($type == 'success') {
            $return_response = sendSuccess($message, $return_object);
        } elseif ($type == 'error') {
            $return_response = sendError($message, $return_object);
        } elseif ($type == 'custom') {
            $return_response = customValidationError($return_object);
        }

        return $return_response;
    }
}

if (!function_exists('sendResponse')) {
    function sendResponse($message = null, $error = null)
    {
        return  [
            'status' => false,
            'message' => !is_null($message) ? $message : Constants::MESSAGES['generic_error'],
            "error"  => $error
        ];
    }
}

if (!function_exists('checkStatus')) {
    function checkStatus($data)
    {
        return isset($data['status']) && $data['status'] == false ? false : true;
    };
}

if (!function_exists('storeFiles')) {
    function storeFiles($folder, $file)
    {
        return Storage::disk(env('STORAGE_TYPE'))->put($folder, $file);
    }
}