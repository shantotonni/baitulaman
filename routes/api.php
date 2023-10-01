<?php

use App\Http\Controllers\AdvisorsController;
use App\Http\Controllers\Api\Frontend\CustomerAuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HeadController;
use App\Http\Controllers\HostelFeeController;
use App\Http\Controllers\ImamController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuPermissionController;
use App\Http\Controllers\MiscellaniousController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramScheduleController;
use App\Http\Controllers\RamadanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SessionHeadController;
use App\Http\Controllers\ShuraController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\StudentBillController;
use App\Http\Controllers\StudentBillPaymentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\WebMenuController;
use App\Http\Controllers\WebSubMenuController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\SessionsController;
use \App\Http\Controllers\YearController;
use \App\Http\Controllers\SettingController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\CommonController;
use \App\Http\Controllers\SupportController;
use \App\Http\Controllers\SessionFeeController;

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'jwt:api'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::get('app-supporting-data', [SettingController::class, 'appSupportingData']);
});

Route::group(['middleware' => ['jwt:api']], function () {
    // ADMIN USERS
    Route::apiResource('users',UserController::class);
    Route::get('search/users/{query}', [UserController::class,'search']);
    Route::get('get-all-users/', [UserController::class, 'getAllUser']);

    Route::apiResource('student',StudentController::class);
    Route::get('search/student/{query}', [StudentController::class,'search']);
    Route::get('get-schedule-data/{session_id}/{category_id}', [StudentController::class,'getScheduleData']);
    Route::get('get-student-wise-schedule-data/{student_id}', [StudentController::class,'getStudentWiseScheduleData']);
    Route::post('student-details', [StudentController::class,'getStudentDetails']);


//Slider
    Route::apiResource('sliders',SliderController::class);
    Route::get('slider-details/{id}',[SliderController::class,'show']);
    Route::get('search/sliders/{query}', [SliderController::class,'search']);

    //event
    Route::apiResource('events',EventController::class);
    Route::get('event-details/{id}',[EventController::class,'show']);
    Route::get('search/events/{query}', [EventController::class,'search']);

    //program
    Route::apiResource('programs',ProgramController::class);
    Route::get('program-details/{id}',[ProgramController::class,'show']);
    Route::get('search/programs/{query}', [ProgramController::class,'search']);

    //customer
    Route::apiResource('customers',CustomerController::class);
    Route::get('search/customers/{query}', [CustomerController::class,'search']);
    Route::get('get-all-customer-events', [CustomerController::class,'getAllCustomerEvents']);
    //web menu
    Route::apiResource('web-menu',WebMenuController::class);
    Route::get('search/web-menu/{query}', [WebMenuController::class,'search']);
    //web sub menu
    Route::apiResource('web-sub-menu',WebSubMenuController::class);
    Route::get('search/web-sub-menu/{query}', [WebSubMenuController::class,'search']);

    //pages
    Route::apiResource('pages',PageController::class);
    Route::get('page-details/{id}',[PageController::class,'show']);
    Route::get('search/pages/{query}', [PageController::class,'search']);

    //imam
    Route::apiResource('imams',ImamController::class);
    Route::get('imam-details/{id}',[ImamController::class,'show']);
    Route::get('search/imams/{query}', [ImamController::class,'search']);

    //BLOG
    Route::apiResource('blogs',BlogController::class);
    Route::get('blog-details/{id}',[BlogController::class,'show']);
    Route::get('search/blogs/{query}', [BlogController::class,'search']);

    //volunteer
    Route::apiResource('volunteers',VolunteerController::class);
    Route::get('volunteer-details/{id}',[VolunteerController::class,'show']);
    Route::get('search/blogs/{query}', [VolunteerController::class,'search']);

    //advisor
    Route::apiResource('advisors',AdvisorsController::class);
    Route::get('search/advisors/{query}', [AdvisorsController::class,'search']);

    //shura
    Route::apiResource('shuras',ShuraController::class);
    Route::get('search/shuras/{query}', [ShuraController::class,'search']);

    //schedule
    Route::apiResource('program-schedule',ProgramScheduleController::class);
    Route::get('search/program-schedule/{query}', [ProgramScheduleController::class,'search']);
    //ramadan
    Route::apiResource('ramadans',RamadanController::class);
    Route::get('search/ramadans/{query}', [RamadanController::class,'search']);
    //testimonial
    Route::apiResource('testimonial',TestimonialController::class);
    Route::get('testimonial-details/{id}',[TestimonialController::class,'show']);

    Route::get('search/testimonial/{query}', [TestimonialController::class,'search']);

    //menu resource route
    Route::apiResource('menu', MenuController::class);
    Route::get('search/menu/{query}', [MenuController::class,'search']);
    Route::get('get-all-menu', [MenuController::class,'getAllMenu']);

    //menu permission route
    Route::get('get-user-menu-details/{UserID}', [MenuPermissionController::class, 'getUserMenuPermission']);
    Route::get('sidebar-get-all-user-menu', [MenuPermissionController::class,'getSidebarAllUserMenu']);
    Route::post('save-user-menu-permission', [MenuPermissionController::class,'saveUserMenuPermission']);

    Route::get('get-all-session', [CommonController::class,'getAllSession']);


    //new route created by shanto
    Route::get('contacts', [\App\Http\Controllers\Api\ContactController::class,'list']);
    Route::get('mailing', [\App\Http\Controllers\Api\MailingController::class,'list']);
    Route::get('questions', [\App\Http\Controllers\Api\QuestionController::class,'list']);
    Route::post('question-reply', [\App\Http\Controllers\Api\QuestionController::class,'replyStore']);

});

//For Frontend

//customer login
Route::post('auth/login', [CustomerAuthController::class, 'login']);
Route::post('auth/logout', [CustomerAuthController::class, 'logout']);
Route::get('auth/user', [CustomerAuthController::class, 'me']);
Route::post('auth/registration', [CustomerAuthController::class, 'registration']);

Route::group(['middleware' => 'CustomerAuth'], function () {
    Route::post('auth/profile-update', [CustomerAuthController::class, 'updateProfile']);
    Route::get('get-customer-donation-list', [CustomerAuthController::class, 'customerDonationList']);
    Route::get('get-customer-program-list', [CustomerAuthController::class, 'customerProgramList']);
    Route::get('donate-print/{id}', [CustomerAuthController::class, 'donatePrint']);

    Route::get('join-events', [CustomerController::class, 'joinEvents']);
});



Route::get('get-pages', [\App\Http\Controllers\Api\Frontend\PagesController::class, 'getPages']);
Route::get('get-advisory-board', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getAdvisoryBoard']);
Route::get('get-shura-committee', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getShuraCommittee']);
Route::get('get-imam', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getImam']);
Route::post('ask-the-imam', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'askTheImam']);
Route::get('get-program-schedule', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getProgramSchedule']);
Route::get('get-ramadan-calendar', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getRamadanCalendar']);
Route::get('get-our-program', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getOurProgram']);
Route::get('get-program-details', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getOurProgramDetails']);
Route::get('get-our-events', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getOurEvents']);
Route::get('get-testimonial', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'getTestimonial']);
Route::post('mailing', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'mailing']);
Route::post('contact', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'contact']);
Route::post('volunteer', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'volunteer']);
Route::post('question', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'question']);
Route::post('question', [\App\Http\Controllers\Api\Frontend\FrontController::class, 'question']);

//stript payment
Route::get('get-session', [StripeController::class, 'getSession']);
Route::get('checkout', [StripeController::class, 'checkout'])->name('checkout');
Route::get('success', [StripeController::class, 'success'])->name('success');

//new
Route::post('payment/initiate', [StripeController::class, 'initiatePayment']);
Route::post('payment/complete', [StripeController::class, 'completePayment']);
Route::post('payment/failure', [StripeController::class, 'failPayment']);

//san
Route::post('payment/store', [StripeController::class, 'paymentStore']);