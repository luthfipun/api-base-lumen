<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function respondOkWithData($data){
        return $this->respond(200, 'ok', $data);
    }

    public function respondErrorWithMessage($message) {
        return $this->respond(500, $message);
    }

    public function respondSuccessWithMessage($message){
        return $this->respond(200, $message);
    }

    public function respondError() {
        return $this->respond(500, 'error');
    }

    public function respondSuccess() {
        return $this->respond(200, 'ok');
    }

    public function respond($code = 200, $message = '', $data = []) {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], 200);
    }
}
