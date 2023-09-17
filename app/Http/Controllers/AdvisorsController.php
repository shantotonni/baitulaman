<?php

namespace App\Http\Controllers;

use App\Http\Requests\Advisors\AdvisorsStoreRequest;
use App\Http\Resources\Advisors\AdvisorsCollection;
use App\Models\Advisors;
use Illuminate\Http\Request;

class AdvisorsController extends Controller
{

    public function index()
    {
        $advisors = Advisors::latest()->paginate(15);
        return new AdvisorsCollection($advisors);
    }

    public function store(AdvisorsStoreRequest $request)
    {
        $input = $request->all();

        Advisors::create($input);

        return response()->json(['message', 'Advisor created successfully.', 200]);
    }

    public function update(Request $request, Advisors $advisor)
    {
        $input = $request->all();

        $advisor->update($input);

        return response()->json(['message', 'Advisor updated successfully', 200]);
    }

    public function destroy(Advisors $advisor)
    {
        $advisor->delete();
        return response()->json(['message','Advisor deleted successfully',200]);
    }
    public function search($query)
    {
        return new AdvisorsCollection(Advisors::Where('name', 'like', "%$query%")
            ->paginate(10));
    }
}
