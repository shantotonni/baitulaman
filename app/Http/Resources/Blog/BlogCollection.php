<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
          'data'=>$this->collection->transform(function ($blog){
              return[
                'id'=>$blog->id,
                'title'=>$blog->title,
                'description'=>$blog->description,
                'image'=>$blog->image,
                'status'=>$blog->status,
              ];
          })
        ];
    }
}
