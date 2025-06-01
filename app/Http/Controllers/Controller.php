<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function jsonOk($data, $status = 200,  $message = 'OK')
    {
        $result['data'] = $data;
        $result['status'] = $status;
        $result['message'] = $message;
        $result['success'] = true;
        return response()->json($result, $status);
    }

    public function jsonError($error, $status = 500, $message = 'Error')
    {
        $result['error'] = $error;
        $result['status'] = $status;
        $result['message'] = $message;
        $result['success'] = false;
        return response()->json($result, $status);
    }
}
