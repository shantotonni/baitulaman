<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;
use Stripe\PaymentIntent;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Stripe as StripeGateway;

class StripeController extends Controller
{
    public function getSession(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        try {
            \Stripe\Stripe::setApiKey(config('stripe.sk'));

            $productname = 'Demo Product';
            $totalprice = 100;

            $session = \Stripe\Checkout\Session::create([
                'line_items'  => [
                    [
                        'price_data' => [
                            'currency'     => 'USD',
                            'product_data' => [
                                "name" => $productname,
                            ],
                            'unit_amount'  => $totalprice,
                        ],
                        'quantity'   => 1,
                    ],

                ],
                'mode'        => 'one-time',
                'payment_method_types' => ['card'],
                'success_url' => route('success'),
                'cancel_url'  => route('checkout'),
            ]);

            $donate = new Donation();
            $donate->purpose = $request->purpose;
            $donate->amount = $request->amount;
            $donate->customer_id = $request->UserId;
            $donate->save();

            return redirect()->away($session->url);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong! '.$exception->getMessage()
            ],500);
        }
    }

    public function checkout(){

    }

    public function success(){

    }

    public function paymentStore(Request $request){
        return $request->all();
    }
}
