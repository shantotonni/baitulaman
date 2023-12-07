<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubCommitteeCollection;
use App\Models\SubCommittee;
use Illuminate\Http\Request;

class SubCommitteeController extends Controller
{
    public function index(){
        $sub = SubCommittee::latest()->paginate(15);
        return new SubCommitteeCollection($sub);
    }

    public function store(Request $request){
        $this->validate($request,[
            'name'=>'required',
        ]);
        $input = $request->all();
        SubCommittee::create($input);

        return response()->json(['message', 'Sub Committee created successfully.', 200]);
    }

    public function update(Request $request, $id){
        $input = $request->all();
        SubCommittee::where('id',$id)->update($input);
        return response()->json(['message', 'Sub Committee updated successfully', 200]);
    }

    public function destroy($id){
        SubCommittee::where('id',$id)->delete();
        return response()->json(['message','Sub Committee deleted successfully',200]);
    }
}
