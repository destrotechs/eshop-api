<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use destrompesa\mpesa\Mpesa;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
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
        $details = $this->mpesa->stkPush(1,"0701530647");
        return response()->json(['message' => $details]);
    }
}
