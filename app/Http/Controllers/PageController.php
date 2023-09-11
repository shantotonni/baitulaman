<?php

namespace App\Http\Controllers;

use App\Http\Requests\Page\PageStoreRequest;
use App\Http\Resources\Page\PageCollection;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->paginate(5);

        return new PageCollection($pages);
    }

    public function store(PageStoreRequest $request)
    {
        $input = $request->all();
        $input['slug'] = Str::slug($input['title']);

        Page::create($input);

        return response()->json(['message','Page created successfully.',200]);
    }

    public function update(Request $request, Page $page)
    {

        $input = $request->all();

        $input['slug'] = Str::slug($input['title']);

        $page->update($input);

        return response()->json(['message','Page updated successfully',200]);
    }
    public function show($slug)
    {
        $pages = Page::all();
        $page = Page::whereSlug($slug)->first();

        return response()->view('details', compact('pages', 'page'));
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return response()
            ->json(['message','Page deleted successfully',200]);
    }
}
