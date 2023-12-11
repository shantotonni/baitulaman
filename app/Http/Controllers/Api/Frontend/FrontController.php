<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Event\EventCollection;
use App\Http\Resources\GalleryCollection;
use App\Models\Advisors;
use App\Models\AskTheBoard;
use App\Models\Contact;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Imam;
use App\Models\Mailing;
use App\Models\Maktab;
use App\Models\Membership;
use App\Models\Program;
use App\Models\ProgramSchedule;
use App\Models\Question;
use App\Models\Ramadan;
use App\Models\Shura;
use App\Models\Slider;
use App\Models\SubCommittee;
use App\Models\Testimonial;
use App\Models\Volunteer;
use App\Models\WebMenu;
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

    public function getSubCommittee(){
        $sub = SubCommittee::orderBy('created_at','desc')->get();
        return response()->json([
           'sub' => $sub
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
        $our_program = Program::orderBy('ordering','desc')->get();
        return response()->json([
            'our_programs' => $our_program
        ]);
    }

    public function getOurEvents(){
        $events = Event::orderBy('created_at','desc')->get();
        return new EventCollection($events);
    }

    public function getHomePageSlider(Request $request){
        $slider = Slider::query()->first();
        return response()->json([
           'slider' => $slider
        ]);
    }

    public function getOurGallery(){
        $gallery = Gallery::orderBy('created_at','desc')->where('status','Y')->get();
        return new GalleryCollection($gallery);
    }

    public function getTestimonial(){
        $testimonials = Testimonial::orderBy('created_at','desc')->get();
        return response()->json([
            'testimonials' => $testimonials
        ]);
    }

    public function getDonationMenu(){
        $menus = WebMenu::orderBy('ordering','asc')->where('active','Y')->get();
        return response()->json([
            'menus' => $menus
        ]);
    }

    public function getOurProgramDetails(Request $request){
        $program_details = Program::where('id',$request->id)->first();
        return response()->json([
            'details' => $program_details
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
        $question->status = 'pending';
        $question->save();

        return response()->json([
            'status'=>'success',
            'message'=>'Successfully Inserted'
        ]);
    }


   public function askTheBoard(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required',
            'subject'=>'required',
            'message'=>'required',
        ]);

        $question = new AskTheBoard();
        $question->name = $request->name;
        $question->email = $request->email;
        $question->subject = $request->subject;
        $question->message = $request->message;
        $question->status = 'pending';
        $question->save();

        return response()->json([
            'status'=>'success',
            'message'=>'Successfully Inserted'
        ]);
    }

    public function storeMaktabRegistration(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'age'=>'required',
        ]);

        $maktab = new Maktab();
        $maktab->name = $request->name;
        $maktab->email = $request->email;
        $maktab->phone = $request->phone;
        $maktab->age = $request->age;
        $maktab->father_name = $request->father_name;
        $maktab->father_email = $request->father_email;
        $maktab->emergency_contact_name = $request->emergency_contact_name;
        $maktab->relation_to_student = $request->relation_to_student;
        $maktab->emergency_contact_number = $request->emergency_contact_number;
        $maktab->save();
        return response()->json([
            'status'=>'success',
            'message'=>'Successfully Inserted'
        ]);
    }

    public function storeMemberShip(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'age'=>'required',
        ]);

        $member = new Membership();
        $member->name = $request->name;
        $member->email = $request->email;
        $member->phone = $request->phone;
        $member->age = $request->age;
        $member->father_name = $request->father_name;
        $member->father_email = $request->father_email;
        $member->save();
        return response()->json([
            'status'=>'success',
            'message'=>'Successfully Inserted'
        ]);
    }
}
