<?php

namespace App\Http\Controllers;

use App\Http\Resources\MembershipCollection;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function list(){
        $member = Membership::query()->paginate(10);
        return new MembershipCollection($member);
    }

    public function exportMemberShip(){
        $member = Membership::query()->get();
        return new MembershipCollection($member);
    }
}
