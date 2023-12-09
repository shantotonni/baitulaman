<?php

namespace App\Http\Resources\WebMenu;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WebmenuCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($webmenu){
                return [
                    'id'=>$webmenu->id,
                    'name'=>$webmenu->name,
                    'url'=>$webmenu->url,
                    'active'=>$webmenu->active,
                ];
            })
        ];
    }
}
