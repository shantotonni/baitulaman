<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($contact){
                return [
                    'id'=>$contact->id,
                    'name'=>$contact->name,
                    'email'=>$contact->email,
                    'subject'=>$contact->subject,
                    'message'=>$contact->message,
                    'created_at'=>date('Y-m-d',strtotime($contact->created_at)),
                ];
            })
        ];
    }
}
