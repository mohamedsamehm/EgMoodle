<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Student;
use App\Models\Professor;
use App\Models\Course;
// use Illuminate\Support\Facades\DB;

use Auth;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function student_schedule(Request $request)
    {
        $student = Student::where('user_id', $request->student_id)->first();
        $scheduleLectures = Schedule::join('enrollments', 'enrollments.section_id', '=', 'schedules.section_id')
        ->whereColumn('enrollments.course_id', 'schedules.course_id')
        // ->join('lectures', 'lectures.section_id', '=', 'schedules.section_id')
        // ->whereColumn('lectures.course_id', 'schedules.course_id')
        // ->join('professors', 'professors.id', '=', 'lectures.professor_id')
        // ->join('users', 'users.id', '=', 'professors.user_id')
        ->join('courses', 'courses.id', '=', 'schedules.course_id')
        ->join('sections', 'sections.id', '=', 'schedules.section_id')
        ->join('periods', 'periods.id', '=', 'schedules.period_from')
        ->where('enrollments.student_id', $student->id)
        ->where('schedules.isLecture', 1)
        ->get(['schedules.*', 'periods.time', 'courses.name as course_name', 'sections.*']);
        $scheduleSectionsAndLabs = Schedule::join('enrollments', 'enrollments.section_id', '=', 'schedules.section_id')
        ->whereColumn('enrollments.course_id', 'schedules.course_id')
        // ->join('section_and_labs', 'section_and_labs.section_id', '=', 'schedules.section_id')
        // ->whereColumn('section_and_labs.course_id', 'schedules.course_id')
        // ->join('engineers', 'engineers.id', '=', 'section_and_labs.engineer_id')
        // ->join('users', 'users.id', '=', 'engineers.user_id')
        ->join('courses', 'courses.id', '=', 'schedules.course_id')
        ->join('sections', 'sections.id', '=', 'schedules.section_id')
        ->join('periods', 'periods.id', '=', 'schedules.period_from')
        ->where('enrollments.student_id', $student->id)
        ->where('schedules.isSection', 1)
        ->orWhere('schedules.isLab', 1)
        ->get(['schedules.*', 'periods.time', 'courses.name as course_name', 'sections.*']);
        return response()->json([$scheduleLectures, $scheduleSectionsAndLabs]);
    }
    public function professor_schedule(Request $request)
    {
        $professor = Professor::where('user_id', $request->id)->first();
        $schedule = Schedule::join('lectures', 'lectures.section_id', '=', 'schedules.section_id')
        ->whereColumn('lectures.course_id', 'schedules.course_id')
        ->join('courses', 'courses.id', '=', 'schedules.course_id')
        ->join('sections', 'sections.id', '=', 'schedules.section_id')
        ->join('periods', 'periods.id', '=', 'schedules.period_from')
        ->where('lectures.professor_id', $professor->id)
        ->where('schedules.isLecture', 1)
        ->get(['schedules.*', 'periods.time', 'courses.name as course_name', 'sections.*']);
        return response()->json([$schedule]);
    }
    public function student_timetable(Request $request)
    {
        $student = Student::where('user_id', $request->student_id)->first();
        $schedule = Schedule::join('enrollments', 'enrollments.section_id', '=', 'schedules.section_id')
        ->whereColumn('enrollments.course_id', 'schedules.course_id')
        ->join('courses', 'courses.id', '=', 'schedules.course_id')
        ->join('sections', 'sections.id', '=', 'schedules.section_id')
        ->join('periods', 'periods.id', '=', 'schedules.period_from')
        ->where('enrollments.student_id', $student->id)
        ->get(['schedules.*', 'periods.time', 'courses.name as course_name', 'sections.*']);
        return response()->json([$schedule]);
    }
}
