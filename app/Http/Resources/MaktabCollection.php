<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MaktabCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($maktab){
                return [
                    'id'=>$maktab->id,
                    'name'=>$maktab->name,
                    'phone'=>$maktab->phone,
                    'email'=>$maktab->email,
                    'age'=>$maktab->age,
                    'father_name'=>$maktab->father_name,
                    'father_email'=>$maktab->father_email,
                    'emergency_contact_name'=>$maktab->emergency_contact_name,
                    'relation_to_student'=>$maktab->relation_to_student,
                    'emergency_contact_number'=>$maktab->emergency_contact_number,
                    'created_at'=>date('Y-m-d',strtotime($maktab->created_at)),
                ];
            })
        ];
    }
}
