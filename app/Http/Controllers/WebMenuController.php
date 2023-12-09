<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebMenu\WebmenuStoreRequest;
use App\Http\Resources\WebMenu\WebmenuCollection;
use App\Models\WebMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebMenuController extends Controller
{

    public function index()
    {
        $webmenus = WebMenu::latest()->paginate(10);

        return new WebmenuCollection($webmenus);
    }

    public function store(WebmenuStoreRequest $request)
    {
        $input = $request->all();
        WebMenu::create($input);
        return response()->json(['message','Menu created successfully.',200]);
    }


    public function update(Request $request, WebMenu $webMenu)
    {
        $input = $request->all();
        $webMenu->update($input);
        return response()->json(['message','Menu updated successfully',200]);
    }

    public function destroy( WebMenu $webMenu)
    {
        $webMenu->delete();
        return response()->json(['message','Menu deleted successfully',200]);
    }

    public function search($query)
    {
        return new WebmenuCollection(WebMenu::Where('name', 'like', "%$query%")->paginate(10));
    }


}
