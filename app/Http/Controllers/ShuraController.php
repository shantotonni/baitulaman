<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shura\ShuraStoreRequest;
use App\Http\Resources\Shura\ShuraCollection;
use App\Models\Shura;
use Illuminate\Http\Request;

class ShuraController extends Controller
{

    public function index()
    {
        $shuras = Shura::latest()->paginate(15);
        return new ShuraCollection($shuras);
    }

    public function store(ShuraStoreRequest $request)
    {
        $input =$request ->all();
        Shura::create($input);
        return response()->json(['Shura Committee Created Successfully',200]);
    }

    public function update(Request $request, Shura $shura)
    {
        $input = $request->all();
        $shura->update($input);
        return response()->json(['Shura Committee Updated Successfully',200]);
    }

    public function destroy(Shura $shura)
    {
        $shura->delete();
        return response()->json(['message','Shura Committee deleted successfully',200]);
    }
    public function search($query)
    {
        return new ShuraCollection(Shura::Where('name', 'like', "%$query%")
            ->paginate(10));
    }
}
