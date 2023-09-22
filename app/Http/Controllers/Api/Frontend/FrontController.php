<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Advisors;
use App\Models\Contact;
use App\Models\Event;
use App\Models\Imam;
use App\Models\Mailing;
use App\Models\Program;
use App\Models\ProgramSchedule;
use App\Models\Question;
use App\Models\Ramadan;
use App\Models\Shura;
use App\Models\Volunteer;
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

    public function mailing(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required',
            'message'=>'required',
        ]);

        $mailing = new Mailing();
        $mailing->name = $request->name;
        $mailing->email = $request->email;
        $mailing->message = $request->message;
        $mailing->save();

        return response()->json([
            'status'=>'success',
            'message'=>'Successfully Inserted'
        ]);
    }

    public function contact(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required',
            'subject'=>'required',
            'message'=>'required',
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();

        return response()->json([
           'status'=>'success',
           'message'=>'Successfully Inserted'
        ]);
    }

    public function volunteer(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required',
            'message'=>'required',
        ]);

        $volunteer = new Volunteer();
        $volunteer->name = $request->name;
        $volunteer->email = $request->email;
        $volunteer->message = $request->message;
        $volunteer->save();

        return response()->json([
           'status'=>'success',
           'message'=>'Successfully Inserted'
        ]);
    }

    public function question(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required',
            'subject'=>'required',
            'message'=>'required',
        ]);

        $question = new Question();
        $question->name = $request->name;
        $question->email = $request->email;
        $question->subject = $request->subject;
        $question->message = $request->message;
        $question->save();

        return response()->json([
            'status'=>'success',
            'message'=>'Successfully Inserted'
        ]);
    }
}
