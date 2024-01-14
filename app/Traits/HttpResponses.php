<?php

namespace App\Traits;

trait HttpResponses{
    protected function success($data , $message = null, $code = 200){
        return response()->json([
            'status' => 'Request was succesful :)' ,
            'message' => $message ,
            'data' => $data ,
        ] , $code);
    }

    protected function errors($data , $message = null, $code ){
        return response()->json([
            'status' => 'Error has occurred :(' ,
            'message' => $message ,
            'data' => $data ,
        ] , $code);
    }
}
?>