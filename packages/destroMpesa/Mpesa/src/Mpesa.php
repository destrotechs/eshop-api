<?php

namespace destrompesa\mpesa;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class Mpesa
{
    /**
     * Create a new instance of the Mpesa service.
     */
    public function __construct()
    {
        // Initialization code, if necessary
    }

    /**
     * Generate an access token from the M-Pesa API.
     *
     * @return mixed
     */
    public function generateAccessToken()
{
    // Get the current environment (sandbox or live) from the configuration
    $environment = config('mpesa.environment');
    $tokenUrl = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    // Get the Consumer Key and Consumer Secret from the configuration
    $consumerKey = config('mpesa.consumer_key');
    $consumerSecret = config('mpesa.consumer_secret');

    // Base64 encode the credentials
    $encodedCredentials = base64_encode("{$consumerKey}:{$consumerSecret}");

    // Perform the HTTP request to generate the access token
    $response = Http::withHeaders([
        'Authorization' => 'Basic ' . $encodedCredentials,
    ])->withOptions([
        'verify' => false, // Disable SSL verification for the sandbox (for development only)
    ])->get($tokenUrl);

    // Decode the response
    $json_response = json_decode($response->body(), true);

    // Log the response for debugging purposes
    // Log::info('M-Pesa Access Token Response: ', $json_response);

    // Check for successful response
    if ($response->successful()) {
        return $json_response; // Return the decoded JSON response as an array
    } else {
        throw new \Exception('Unable to generate access token: ' . $response->body());
    }
}


    /**
     * Initiate a payment using the M-Pesa API.
     *
     * @param float $amount The amount to pay.
     * @param string $phoneNumber The phone number to pay to.
     * @return mixed
     */
    public function express($amount, $phoneNumber)
    {
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '254' . substr($phoneNumber, 1);
        }
        $amount = (float)$amount;

        if ($amount <= 0) {
            throw new \Exception('Amount must be greater than zero.');
        }
        // Generate an access token
        $tokenData = $this->generateAccessToken();
        $accessToken = $tokenData['access_token'];

        // Define the STK push URL based on the environment
        $environment = config('mpesa.environment');
        $paymentUrl = config("mpesa.api_urls.$environment.stk_push_url"); // Corrected to use the STK push URL

        // Prepare the STK push request payload
        $businessShortCode = config('mpesa.shortcode'); // Your M-Pesa shortcode
        $timestamp = date('yymdhis'); // Generate current timestamp
        $password = base64_encode($businessShortCode . config('mpesa.lipa_na_mpesa_key') . $timestamp); // Encode the password
        $transactionType = "CustomerPayBillOnline"; // Transaction type

        $payload = [
            'BusinessShortCode' => $businessShortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => $transactionType,
            'Amount' => 1,
            'PartyA' => $phoneNumber, // Phone number of the sender
            'PartyB' => $businessShortCode, // Business shortcode
            'PhoneNumber' => $phoneNumber,
            'CallBackURL' => config('mpesa.callbacks.result_url'), // Your callback URL
            'AccountReference' => 'Test', // Reference for your account
            'TransactionDesc' => 'Test', // Transaction description
        ];
        // dd($payload);
        // Perform the HTTP request to initiate the STK push
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post($paymentUrl, $payload);
        // dd($response);
        // Log the response for debugging purposes
        // Log::info('STK Push Response: ', ['response' => $response->body()]);

        // Check for successful response and return it
        if ($response->successful()) {
            return json_decode($response->body(), true); // Return as an associative array
        } else {
            throw new \Exception('Unable to initiate payment: ' . $response->body());
        }
    }
    public function checkExpressStatus($checkoutRequestId){
        // Generate an access token
        $tokenData = $this->generateAccessToken();
        $accessToken = $tokenData['access_token'];

        // Define the query STK push URL based on the environment
        $environment = config('mpesa.environment');
        $queryUrl = config("mpesa.api_urls.$environment.query_stk_push_url"); // URL for querying STK push status

        // Prepare the status check request payload
        $payload = [
            'BusinessShortCode' => config('mpesa.shortcode'), // Your M-Pesa shortcode
            'Password' => base64_encode(config('mpesa.shortcode') . config('mpesa.lipa_na_mpesa_key') . date('yymdhis')), // Encode the password
            'Timestamp' => date('yymdhis'), // Generate current timestamp
            'CheckoutRequestID' => $checkoutRequestId, // Checkout request ID to check
        ];

        // Perform the HTTP request to check the status
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post($queryUrl, $payload);

        // Log the response for debugging purposes
        // Log::info('Check Status Response: ', ['response' => $response->body()]);

        // Check for successful response and return it
        if ($response->successful()) {
            return json_decode($response->body(), true); // Return as an associative array
        } else {
            throw new \Exception('Unable to check payment status: ' . $response->body());
        }

    }
    

    public  function logMpesaResponse($data, $filePath)
    {
        // Check if the directory exists, if not, create it
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        // Open the file in append mode
        $fileHandle = fopen($filePath, 'w'); 

        if ($fileHandle) {
            // Write the JSON data to the file
            fwrite($fileHandle, json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL);
            fclose($fileHandle); // Close the file handle
        } else {
            Log::error("Could not open file for writing: $filePath");
        }
    }
}
