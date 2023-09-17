<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function youthClub(){
        $youth_club = Page::where('slug','baitul-aman-youth-club')->first();
        return response()->json([
           'youth_club'=>$youth_club
        ]);
    }

    public function maktabCurriculum(){
        $maktab_curriculum = Page::where('slug','maktab-curriculum')->first();
        return response()->json([
           'maktab_curriculum'=>$maktab_curriculum
        ]);
    }

    public function getAbout(){
        $about = Page::where('slug','about')->first();
        return response()->json([
           'about'=>$about
        ]);
    }
    public function getObjectives(){
        $objectives = Page::where('slug','objectives')->first();
        return response()->json([
           'objectives'=>$objectives
        ]);
    }
}
