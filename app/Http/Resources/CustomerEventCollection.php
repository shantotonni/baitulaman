<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerEventCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($CE){
                return [
                    'id'=>$CE->id,
                    'customer_name'=>isset($CE->customer) ? $CE->customer->name: '',
                    'title'=>isset($CE->event) ? $CE->event->title: '',
                    'event_date'=>isset($CE->event) ? $CE->event->event_date: '',
                ];
            })
        ];
    }
}
