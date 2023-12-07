<?php

namespace App\Http\Controllers;

use App\Http\Resources\MaktabCollection;
use App\Models\Maktab;
use Illuminate\Http\Request;

class MaktabController extends Controller
{
    public function list(){
        $maktab = Maktab::query()->paginate(10);
        return new MaktabCollection($maktab);
    }

    public function exportMaktab(){
        $maktab = Maktab::query()->get();
        return new MaktabCollection($maktab);
    }
}
