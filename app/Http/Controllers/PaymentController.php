<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use destrompesa\mpesa\Mpesa;
use Stripe\PaymentIntent;
use App\Traits\HttpResponses;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    use HttpResponses;
    protected $mpesa;
    public function __construct(Mpesa $mpesa){
        $this->mpesa = $mpesa;
    }
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount, // Amount in cents
            'currency' => 'kes',
            'payment_method' => 'pm_card_visa',
        ]);

        return response()->json(['clientSecret' => $paymentIntent->client_secret]);
    }

    public function mpesaPayment(Request $request){
        $request->validate([
            'amount'=>'required',
            'phone_number'=>'required',
            "orderID"=>'required',
        ]);
        $amount = $request->amount;
        $phone_number = $request->phone_number;
        $details = $this->mpesa->express($amount,$phone_number);
        $CheckoutRequestID = $details['CheckoutRequestID'];
        if (isset($details['CheckoutRequestID'])){
            sleep(15);
            $status = $this->mpesa->checkExpressStatus($details['CheckoutRequestID']);
            if($status['ResultCode']==0){
                $payment = new Payment;
                $payment->order_id=$request->orderID;
                $payment->currency='Ksh';
                $payment->payment_mode_id = $request->payment_mode_id;
                $payment->payment_id = $CheckoutRequestID;
                $payment->amount = $amount;
                // $payment->paid_on = date('Y-m-d H:i:s');
                // $payment->payment_details = json_encode($status);

                $order = Order::find($request->orderID);

                $order->payments()->save($payment);

                return $this->success(array($phone_number,$amount),"Payment successful");
            }else{
                return $this->error($status,"payment failed! ".$status['ResultDesc'],400);
            }
        }
        
        return $this->success($details,"payment failed! ",400);
    }


// Helper function to extract item from CallbackMetadata
protected function extractItem(array $items, string $name, $default = null)
{
    foreach ($items as $item) {
        if ($item['Name'] === $name) {
            return $item['Value'] ?? $default;
        }
    }
    return $default;
}

public function handleCallback(Request $request)
{
    // Set the response header to indicate success
    header("HTTP/1.1 200 OK");

    // Path to the file where you want to write the data
    $filePath = storage_path('logs/mpesa_response.txt');

    // Get the raw POST data
    $rawPostData = $request->getContent();

    // Decode the JSON data
    $data = json_decode($rawPostData, true);

    // Check if decoding was successful
    if ($data) {
        // Log the response data to a file
        $this->mpesa->logMpesaResponse($data, $filePath);
        Log::info('MPesa Callback Data:', $data);

        // Verify the response contains the necessary fields
        if (isset($data['Body']['stkCallback']) && $data['Body']['stkCallback']['ResultCode'] == 0) {
            $callbackData = $data['Body']['stkCallback']['CallbackMetadata']['Item'];
            $CheckoutRequestID = $data['Body']['stkCallback']['CheckoutRequestID'];

            // Extract callback metadata
            $amount = $this->extractItem($callbackData, 'Amount');
            $transactionDate = $this->extractItem($callbackData, 'TransactionDate');
            $receiptNumber = $this->extractItem($callbackData, 'MpesaReceiptNumber');
            $phoneNumber = $this->extractItem($callbackData, 'PhoneNumber');

            // Find and update the payment record by CheckoutRequestID
            $payment = Payment::where('payment_id', $CheckoutRequestID)->first();
            if ($payment) {
                $payment->amount = $amount;
                $payment->paid_on = date('Y-m-d H:i:s', strtotime($transactionDate));
                $payment->payment_details = json_encode($data);
                $payment->save();

                // Optionally update related order status
                $order = Order::find($payment->order_id);
                if ($order) {
                    // Update order status or other details if needed
                }

                return $this->success($data, "MPesa Callback Success");
            } else {
                Log::error("Payment record not found for CheckoutRequestID: $CheckoutRequestID");
                return $this->error([], "Payment record not found", 400);
            }
        } else {
            Log::error("MPesa Callback Error: Invalid transaction data", ['data' => $data]);
            return $this->error($data, "MPesa Callback Error", 400);
        }
    } else {
        // Log error if JSON decoding fails
        Log::error("Failed to decode JSON: $rawPostData");
        return $this->error($rawPostData, "Failed to decode JSON", 400);
    }
}
public function register_callback_urls(Request $request){
    $response = $this->mpesa->registerUrls();
    return response()->json($response);
}

}
