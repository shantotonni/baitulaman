<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DonationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($donation){
                return [
                    'id'=>$donation->id,
                    'purpose'=>$donation->purpose,
                    'amount'=>$donation->amount,
                    'customer_name'=>isset($donation->customer) ? $donation->customer->name: '',
                    'customer_email'=>isset($donation->customer) ? $donation->customer->email: '',
                    'customer_phone'=>isset($donation->customer) ? $donation->customer->phone: '',
                ];
            })
        ];
    }
}
