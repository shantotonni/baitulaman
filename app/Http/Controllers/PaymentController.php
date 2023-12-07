<?php

namespace App\Http\Controllers;

use App\Http\Resources\DonationCollection;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function charge(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'amount' => 'required',
        ]);
        DB::beginTransaction();

        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://scl-sandbox.dev.clover.com/v1/charges",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([
                    'ecomind' => 'ecom',
                    'metadata' => [
                        'existingDebtIndicator' => false
                    ],
                    'amount' => (int)$request->amount * 100,
                    'currency' => 'USD',
                    'capture' => false,
                    'source' => $request->token
                ]),
                CURLOPT_HTTPHEADER => [
                    "accept: application/json",
                    "authorization: Bearer 4570f438-10a6-9bb5-795f-f3a048bf2e1d",
                    "content-type: application/json",
                    "x-forwarded-for: 127.0.0.1"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return response()->json([
                    'status' => 'error',
                    'error' => $err
                ]);
            } else {
                $data = json_decode($response);

                $donate = new Donation();
                $donate->name = $request->name;
                $donate->email = $request->email;
                $donate->mobile = $request->mobile;
                $donate->amount = $request->amount;
                $donate->ref_num = $data->ref_num;
                $donate->payment_method_details = $data->payment_method_details;
                $donate->payment_id = $data->id;
                $donate->currency = $data->currency;
                $donate->auth_code = $data->auth_code;
                $donate->status = $data->status;
                $donate->save();

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Successfully Submitted.'
                ]);
            }
        }catch (\Exception $exception){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong! '.$exception->getMessage()
            ],500);
        }
    }

    public function getApiKey(){
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://sandbox.dev.clover.com/pakms/apikey', [
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Bearer 0f7cf22c-d5f7-e1d2-dca9-e796fde55e18',
            ],
        ]);
        return $response->getBody();
    }

    public function donationList(){
        $donations = Donation::query()->paginate(15);
        return new DonationCollection($donations);
    }

    public function exportDonation(){
        $donations = Donation::query()->get();
        return new DonationCollection($donations);
    }
}
