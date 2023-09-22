<?php

namespace App\Http\Controllers;

use App\Http\Requests\Volunteer\VolunteerStoreRequest;
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

    public function store(VolunteerStoreRequest $request)
    {

        $volunteer = New Volunteer();
        $volunteer->name = $request->name;
        $volunteer->email = $request->email;
        $volunteer->message = $request->message;
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
        $volunteer->name = $request->name;
        $volunteer->email = $request->email;
        $volunteer->message = $request->message;
        $volunteer->save();
        return response()->json([
            'message' => 'Volunteer Info Updated Successfully'
        ],200);
    }

    public function destroy($id)
    {
        $volunteer = Volunteer::where('id', $id)->first();
        $volunteer->delete();
        return response()->json(['message' => 'Volunteer Deleted Successfully']);
    }


    public function search($query)
    {
        return new VolunteerCollection(Volunteer::Where('name', 'like', "%$query%")
            ->paginate(10));
    }
}
