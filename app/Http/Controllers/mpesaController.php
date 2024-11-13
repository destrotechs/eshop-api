<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class mpesaController extends Controller
{
    public function confirmation(Request $request)
    {
        // Log the request for debugging purposes
        Log::info('M-Pesa Confirmation:', $request->all());

        // Format the data as a JSON string
        $data = json_encode($request->all(), JSON_PRETTY_PRINT);

        // Save the data to a file with a unique name based on the transaction ID or timestamp
        $filename = 'mpesa_confirmation_' . now()->format('Ymd_His') . '.json';
        Storage::disk('local')->put('mpesa/'.$filename, $data);

        // Return a success response to M-Pesa
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Confirmation Received Successfully'
        ]);
    }

    public function validation(Request $request)
    {
        // Log the request for debugging purposes
        Log::info('M-Pesa Validation:', $request->all());

        // Format the data as a JSON string
        $data = json_encode($request->all(), JSON_PRETTY_PRINT);

        // Save the data to a file with a unique name based on the transaction ID or timestamp
        $filename = 'mpesa_validation_' . now()->format('Ymd_His') . '.json';
        Storage::disk('local')->put('mpesa/'.$filename, $data);

        // Perform any necessary validation (for this example, assume validation is successful)
        $isValid = true;

        if ($isValid) {
            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Validation Successful'
            ]);
        } else {
            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Validation Failed'
            ]);
        }
    }
}
