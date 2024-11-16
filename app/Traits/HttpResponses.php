<?php

namespace App\Traits;

trait HttpResponses{
    protected function success($data,$message='The action was executed successfully',$success=null,$code=200){
        return response()->json([
            'status' => 'success',
            'message'=>$message,
            'data'=>$data,
            'success'=>$success,
        ], $code);
    }

    protected function error($data=null,$message='There was an error performing the action',$error=null,$code=401){
        return response()->json([
            'status' => 'Request failed ....',
            'message'=>$message,
            'error'=>$error,
            'data'=>$data,
        ], $code);
    }
}
