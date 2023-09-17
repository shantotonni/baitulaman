<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgramSchedule\ProgramScheduleStoreRequest;
use App\Http\Requests\Shura\ShuraStoreRequest;
use App\Http\Resources\ProgramSchedule\ProgramScheduleCollection;
use App\Models\ProgramSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;

class ProgramScheduleController extends Controller
{

    public function index()
    {
        $schedules = ProgramSchedule::latest()->paginate(15);
        return new ProgramScheduleCollection($schedules);
    }


    public function store(ProgramScheduleStoreRequest $request)
    {
        $input = $request->all();
        ProgramSchedule::create($input);
        return response()->json(['Program Schedule Successfully Created', 200]);
    }


    public function update(Request $request, ProgramSchedule $schedule)
    {
        $schedule = ProgramSchedule::where('id', $request->id)->first();
        $schedule->name = $request->name;
        $schedule->day = $request->day;
        $schedule->time = $request->time;
        $schedule->save();
        return response()->json(['Program Schedule Successfully Updated', 200]);
    }


    public function destroy($id)
    {
        $schedule = ProgramSchedule::where('id',$id)->first();
        $schedule->delete();
        return response()->json(['Program Schedule Successfully Deleted', 200]);

    }

    public function search($query)
    {
        return new ProgramScheduleCollection(ProgramSchedule::Where('name', 'like', "%$query%")
            ->paginate(10));
    }
}
