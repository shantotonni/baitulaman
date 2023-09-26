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
        try {
            \Stripe\Stripe::setApiKey(config('stripe.sk'));

            $productname = 'Demo Product';
            $totalprice = 10;

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
                'mode'        => 'payment',
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

    public function paymentStore(Request $request){
        return $request->all();
    }
}
