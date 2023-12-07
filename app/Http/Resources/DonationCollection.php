<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DonationCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($donation){
                return [
                    'id'=>$donation->id,
                    'purpose'=>$donation->purpose,
                    'amount'=>$donation->amount,
                    'date'=>date('Y-m-d',strtotime($donation->created_at)),
                    'name'=>$donation->name,
                    'email'=>$donation->email,
                    'mobile'=>$donation->mobile,
                ];
            })
        ];
    }
}
