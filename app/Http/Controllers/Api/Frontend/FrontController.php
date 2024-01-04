<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\BlogCollection;
use App\Http\Resources\Event\EventCollection;
use App\Http\Resources\GalleryCollection;
use App\Http\Resources\Program\ProgramCollection;
use App\Models\Advisors;
use App\Models\AskTheBoard;
use App\Models\Blog;
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
use Carbon\Carbon;
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
        $current_date = Carbon::now()->format('Y-m-d');
        $current_program = Program::orderBy('ordering','desc')
            ->where('program_date','>=',$current_date)
            ->get();
        $previous_program = Program::orderBy('ordering','desc')
            ->where('program_date','<=',$current_date)
            ->get();

        return response()->json([
            'current_program'=>new ProgramCollection($current_program),
            'previous_program'=>new ProgramCollection($previous_program)
        ]);
    }

    public function getOurProgramDetails(Request $request){
        $program_details = Program::where('id',$request->id)->first();
        return response()->json([
            'details' => $program_details
        ]);
    }

    public function getOurEvents(){
        $current_date = Carbon::now()->format('Y-m-d');
        $current_events = Event::orderBy('ordering','asc')
            ->where('event_date','>=',$current_date)
            ->get();
        $previous_events = Event::orderBy('ordering','asc')
            ->where('event_date','<=',$current_date)
            ->get();
        return response()->json([
            'current_events' => new EventCollection($current_events),
            'previous_events' => new EventCollection($previous_events)
        ]);
    }

    public function getOurBlog(){
        $blogs = Blog::where('status','Active')->orderBy('created_at','desc')->get();
        return new BlogCollection($blogs);
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

    public function getFrontendMenu(){
        $menus = WebMenu::orderBy('ordering','asc')->with('sub_menu')->where('active','Y')->get();
        return response()->json([
            'menus' => $menus
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
            'mobile'=>'required',
            'subject'=>'required',
            'message'=>'required',
            'capture'=>'required',
        ]);

        $question = new Question();
        $question->name = $request->name;
        $question->email = $request->email;
        $question->mobile = $request->mobile;
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
            'gender'=>'required',
            'date_of_birth'=>'required',
            'age'=>'required',
            'previous_education'=>'required',
            'father_name'=>'required',
            'mother_name'=>'required',
            'address'=>'required',
            'father_email'=>'required',
            'father_phone'=>'required',
            'mother_phone'=>'required',
            'medical_condition'=>'required',
            'emergency_contact_name'=>'required',
            'emergency_contact_number'=>'required',
            'relation_to_student'=>'required',
            'apply'=>'required',
        ]);

        $maktab = new Maktab();
        $maktab->name = $request->name;
        $maktab->gender = $request->gender;
        $maktab->date_of_birth = $request->date_of_birth;
        $maktab->age = $request->age;
        $maktab->previous_education = $request->previous_education;
        $maktab->previous_education_details = $request->previous_education_details;
        $maktab->father_name = $request->father_name;
        $maktab->mother_name = $request->mother_name;
        $maktab->address = $request->address;
        $maktab->father_email = $request->father_email;
        $maktab->father_phone = $request->father_phone;
        $maktab->mother_phone = $request->mother_phone;
        $maktab->medical_condition = $request->medical_condition;
        $maktab->emergency_contact_name = $request->emergency_contact_name;
        $maktab->relation_to_student = $request->relation_to_student;
        $maktab->emergency_contact_number = $request->emergency_contact_number;
        $maktab->apply = $request->apply;
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
            'gender'=>'required',
            'address'=>'required',
            'date_of_birth'=>'required',
        ]);

        $member = new Membership();
        $member->name = $request->name;
        $member->email = $request->email;
        $member->phone = $request->phone;
        $member->age = $request->age;
        $member->address = $request->address;
        $member->gender = $request->gender;
        $member->date_of_birth = $request->date_of_birth;
        $member->father_name = $request->father_name;
        $member->father_email = $request->father_email;
        $member->save();
        return response()->json([
            'status'=>'success',
            'message'=>'Successfully Inserted'
        ]);
    }
}
