<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ramandan\RamadanStoreRequest;
use App\Http\Resources\Ramandan\RamadanCollection;
use App\Models\Ramadan;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class RamadanController extends Controller
{

    public function index()
    {
        $ramadans = Ramadan::latest()->paginate(15);
        return new RamadanCollection($ramadans);
    }


    public function store(RamadanStoreRequest $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/ramadan/').$name);
        } else {
            $name = 'not_found.jpg';
        }
        $ramadan = new Ramadan();
        $ramadan->name = $request->name;
        $ramadan->image= $name;
        $ramadan->save();
        return response()->json(['Ramadan Info Successfully Stored']);

    }


    public function update(Request $request, $id)
    {
        $ramadan = Ramadan::where('id',$id)->first();
        $image = $request->image;

        if ($image != $ramadan->image) {
            if ($request->has('image')) {
                //code for remove old file
                if ($ramadan->image != '' && $ramadan->image != null) {
                    $destinationPath = 'images/ramadan/';
                    $file_old = $destinationPath . $ramadan->image;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->resize(1600,1000)->save(public_path('images/ramadan/') . $name);
            } else {
                $name = $ramadan->image;
            }
        }else{
            $name = $ramadan->image;
        }
        $ramadan->name = $request->name;
        $ramadan->image= $name;
        $ramadan->save();
        return response()->json(['Ramadan Info Successfully Updated']);
    }

    public function destroy($id)
    {
        $ramadan = Ramadan::where('id', $id)->first();
        if ($ramadan->image) {
            $destinationPath = '/images/ramadan/';

            $file = public_path('/') . $destinationPath . $ramadan->image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $ramadan->delete();
        return response()->json(['Ramadan Info Successfully Deleted']);
    }
    public function search($query)
    {
        return new RamadanCollection(Ramadan::Where('name', 'like', "%$query%")
            ->paginate(10));
    }
}
