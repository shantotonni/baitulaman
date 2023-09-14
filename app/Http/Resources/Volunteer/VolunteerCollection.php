<?php

namespace App\Http\Resources\Volunteer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VolunteerCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($volunteer){
                return [
                    'id'=>$volunteer->id,
                    'name'=>$volunteer->name,
                    'mobile'=>$volunteer->mobile,
                    'email'=>$volunteer->email,
                    'address'=>$volunteer->address,
                    'description'=>$volunteer->description,
                    'image'=>$volunteer->image,
                    'educational_qualification'=>$volunteer->educational_qualification,
                    'experience'=>$volunteer->experience,
                    'status'=>$volunteer->status,
                ];
            })
        ];
    }
}
