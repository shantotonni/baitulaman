<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MembershipCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($member){
                return [
                    'id'=>$member->id,
                    'name'=>$member->name,
                    'phone'=>$member->phone,
                    'email'=>$member->email,
                    'age'=>$member->age,
                    'father_name'=>$member->father_name,
                    'father_email'=>$member->father_email,
                    'created_at'=>date('Y-m-d',strtotime($member->created_at)),
                ];
            })
        ];
    }
}
