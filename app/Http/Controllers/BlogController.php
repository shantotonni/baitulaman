<?php

namespace App\Http\Controllers;

use App\Http\Resources\Blog\BlogCollection;
use App\Models\Blog;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BlogController extends Controller
{

    public function index()
    {
        $blog = Blog::Orderby('id','desc')->paginate(15);
        return new BlogCollection($blog);
    }
    public function store(Request $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/blog/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $blog = New Blog();
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->status = $request->status;
        $blog->image =$name;
        $blog->save();
        return response()->json([
            'message' => 'Blog Info Stored Successfully'
        ],200);
    }

    public function update(Request $request, Blog $blog)
    {
        $blog = Blog::where('id',$request->id)->first();
        $image = $request->image;

        if ($image != $blog->image) {
            if ($request->has('image')) {
                //code for remove old file
                if ($blog->image != '' && $blog->image != null) {
                    $destinationPath = 'images/blog/';
                    $file_old = $destinationPath . $blog->image;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->resize(1600,1000)->save(public_path('images/blog/') . $name);
            } else {
                $name = $blog->image;
            }
        }else{
            $name = $blog->image;
        }
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->status = $request->status;
        $blog->image =$name;
        $blog->save();
        return response()->json([
            'message' => 'Blog Info Updated Successfully'
        ],200);
    }

    public function destroy($id)
    {
        $blog = Blog::where('id', $id)->first();
        if ($blog->image) {
            $destinationPath = '/images/blog/';

            $file = public_path('/') . $destinationPath . $blog->image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $blog->delete();
        return response()->json(['message' => 'Blog Deleted Successfully']);
    }


    public function search($query)
    {
        return new BlogCollection(Blog::Where('title', 'like', "%$query%")
            ->paginate(10));
    }
}
