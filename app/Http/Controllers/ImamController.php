<?php

namespace App\Http\Controllers;

use App\Http\Requests\Imam\ImamStoreRequest;
use App\Http\Resources\Imam\ImamCollection;
use App\Models\Imam;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImamController extends Controller
{
    public function index()
    {
        $imam = Imam::Orderby('id','desc')->paginate(15);
        return new ImamCollection($imam);
    }

     public function store(ImamStoreRequest $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/imam/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $imam = New Imam();
        $imam->name = $request->name;
        $imam->mobile = $request->mobile;
        $imam->email = $request->email;
        $imam->address = $request->address;
        $imam->description = $request->description;
        $imam->educational_qualification = $request->educational_qualification;
        $imam->experience = $request->experience;
        $imam->status = $request->status;
        $imam->image =$name;
        $imam->save();
        return response()->json([
            'message' => 'Imam Info Stored Successfully'
        ],200);
    }

    public function update(Request $request, Imam $id)
    {

        $imam = Imam::where('id',$request->id)->first();
        $image = $request->image;

        if ($image != $imam->image) {
            if ($request->has('image')) {
                //code for remove old file
                if ($imam->image != '' && $imam->image != null) {
                    $destinationPath = 'images/imam/';
                    $file_old = $destinationPath . $imam->image;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->resize(1600,1000)->save(public_path('images/imam/') . $name);
            } else {
                $name = $imam->image;
            }
        }else{
            $name = $imam->image;
        }
        $imam->name = $request->name;
        $imam->mobile = $request->mobile;
        $imam->email = $request->email;
        $imam->address = $request->address;
        $imam->description = $request->description;
        $imam->educational_qualification = $request->educational_qualification;
        $imam->experience = $request->experience;
        $imam->status = $request->status;
        $imam->image =$name;
        $imam->save();
        return response()->json([
            'message' => 'Imam Info Updated Successfully'
        ],200);

    }
    public function show($id)
    {
        $imams = Imam::where('id', $id)->first();
        return response()->json([
            'data'=>$imams
        ]);
    }

    public function destroy($id)
    {
        $imam = Imam::where('id', $id)->first();
        if ($imam->image) {
            $destinationPath = '/images/imam/';

            $file = public_path('/') . $destinationPath . $imam->image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $imam->delete();
        return response()->json(['message' => 'imam Deleted Successfully']);
    }


    public function search($query)
    {
        return new ImamCollection(Imam::Where('name', 'like', "%$query%")
            ->paginate(10));
    }
}
