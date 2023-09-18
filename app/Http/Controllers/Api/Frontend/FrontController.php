<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Advisors;
use App\Models\Event;
use App\Models\Imam;
use App\Models\Program;
use App\Models\ProgramSchedule;
use App\Models\Ramadan;
use App\Models\Shura;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function getAdvisoryBoard(){
        $advisory_board = Advisors::orderBy('created_at','desc')->get();
        return response()->json([
           'advisory_board' => $advisory_board
        ]);
    }

    public function getShuraCommittee(){
        $shura_committee = Shura::orderBy('created_at','desc')->get();
        return response()->json([
           'shura_committee' => $shura_committee
        ]);
    }

    public function getImam(){
        $imam = Imam::orderBy('created_at','desc')->first();
        return response()->json([
           'imam' => $imam
        ]);
    }

    public function askTheImam(Request $request){
        return $request->all();
    }

    public function getProgramSchedule(){
        $program_schedule = ProgramSchedule::orderBy('created_at','desc')->get();
        return response()->json([
            'program_schedule' => $program_schedule
        ]);
    }

    public function getRamadanCalendar(){
        $ramadan_calendar = Ramadan::orderBy('created_at','desc')->first();
        return response()->json([
            'ramadan_calendar' => $ramadan_calendar
        ]);
    }

    public function getOurProgram(){
        $our_program = Program::orderBy('created_at','desc')->get();
        return response()->json([
            'our_programs' => $our_program
        ]);
    }

    public function getOurEvents(){
        $events = Event::orderBy('created_at','desc')->get();
        return response()->json([
            'events' => $events
        ]);
    }
}
