<?php

namespace App\Traits;

trait HttpResponses{
    protected function success($data,$message='The action was executed successfully',$code=200){
        return response()->json([
            'status' => 'success',
            'message'=>$message,
            'data'=>$data,
        ], $code);
    }

    protected function error($data=null,$message='There was an error performing the action',$code=401){
        return response()->json([
            'status' => 'Request failed ....',
            'message'=>$message,
            'data'=>$data,
        ], $code);
    }
}
