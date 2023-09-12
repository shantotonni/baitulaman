<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebMenu\WebsubmenuStoreRequest;
use App\Http\Resources\WebMenu\WebsubmenuCollection;
use App\Models\WebSubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebSubMenuController extends Controller
{

    public function index()
    {
        $submenu = WebSubMenu::latest()->paginate(5);

        return new WebSubMenuCollection($submenu);
    }


    public function store(WebsubmenuStoreRequest $request)
    {
        $input = $request->all();
        $input['slug'] = Str::slug($input['name']);

        WebSubMenu::create($input);

        return response()->json(['message','Web Sub Menu created successfully.',200]);

    }

    public function update(Request $request, WebSubMenu $webSubMenu)
    {
        $input = $request->all();

        $input['slug'] = Str::slug($input['name']);

        $webSubMenu->update($input);

        return response()->json(['message','Web Sub Menu updated successfully',200]);

    }


    public function destroy(WebSubMenu $webSubMenu)
    {
        $webSubMenu->delete();
        return response()
            ->json(['message','Web Sub Menu deleted successfully',200]);

    }
    public function search($query)
    {
        return new WebSubMenuCollection(WebSubMenu::Where('name', 'like', "%$query%")
            ->paginate(10));
    }
}
