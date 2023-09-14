<?php

namespace App\Http\Controllers;

use App\Http\Resources\Volunteer\VolunteerCollection;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class VolunteerController extends Controller
{
    public function index()
    {
        $volunteerS = Volunteer::Orderby('id','desc')->paginate(15);
        return new VolunteerCollection($volunteerS);
    }

    public function store(Request $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/volunteer/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $volunteer = New Volunteer();
        $volunteer->name = $request->name;
        $volunteer->mobile = $request->mobile;
        $volunteer->email = $request->email;
        $volunteer->address = $request->address;
        $volunteer->description = $request->description;
        $volunteer->educational_qualification = $request->educational_qualification;
        $volunteer->experience = $request->experience;
        $volunteer->status = $request->status;
        $volunteer->image =$name;
        $volunteer->save();
        return response()->json([
            'message' => 'Volunteer Info Stored Successfully'
        ],200);
    }


    public function show($id)
    {
        $volunteers = Volunteer::where('id', $id)->first();
        return response()->json([
            'data'=>$volunteers
        ]);
    }


    public function update(Request $request, Volunteer $volunteer)
    {
        $volunteer = Volunteer::where('id',$request->id)->first();
        $image = $request->image;

        if ($image != $volunteer->image) {
            if ($request->has('image')) {
                //code for remove old file
                if ($volunteer->image != '' && $volunteer->image != null) {
                    $destinationPath = 'images/volunteer/';
                    $file_old = $destinationPath . $volunteer->image;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->resize(1600,1000)->save(public_path('images/volunteer/') . $name);
            } else {
                $name = $volunteer->image;
            }
        }else{
            $name = $volunteer->image;
        }
        $volunteer->name = $request->name;
        $volunteer->mobile = $request->mobile;
        $volunteer->email = $request->email;
        $volunteer->address = $request->address;
        $volunteer->description = $request->description;
        $volunteer->educational_qualification = $request->educational_qualification;
        $volunteer->experience = $request->experience;
        $volunteer->status = $request->status;
        $volunteer->image =$name;
        $volunteer->save();
        return response()->json([
            'message' => 'Volunteer Info Updated Successfully'
        ],200);
    }

    public function destroy($id)
    {
        $volunteer = Volunteer::where('id', $id)->first();
        if ($volunteer->image) {
            $destinationPath = '/images/volunteer/';

            $file = public_path('/') . $destinationPath . $volunteer->image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $volunteer->delete();
        return response()->json(['message' => 'Volunteer Deleted Successfully']);
    }


    public function search($query)
    {
        return new VolunteerCollection(Volunteer::Where('name', 'like', "%$query%")
            ->paginate(10));
    }
}
