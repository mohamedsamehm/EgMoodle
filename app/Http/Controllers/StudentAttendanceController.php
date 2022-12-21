<?php

namespace App\Http\Controllers;

use App\Models\StudentAttendance;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class StudentAttendanceController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'id' => 'required',
            'students' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 401,
                'errors' => $validator->errors(),
            ], 200);				
        }
        foreach ($request->students as $student) {
            if(!StudentAttendance::where('student_id', $student['student_id'])->where('meeting_id', $request->id)->first()) {
                StudentAttendance::create([
                    'student_id' => $student['student_id'],
                    'meeting_id'  => $request->id,
                    'type' => $student['type'],
                ]);
            }
        }
        return response()->json(['status' => 200]);
    }
    public function getAllStudentsForMeetings($id)
    {
        $students = StudentAttendance::join('students', 'students.id', '=', 'student_attendances.student_id')
        ->join('users', 'users.id', '=', 'students.user_id')
        ->where('student_attendances.meeting_id', $id)
        ->get(['users.fullName', 'users.email', 'student_attendances.type']);
        return response()->json([
			'status' => 200,
			'students' => $students,
		], 200);
    }
    public function viewMyAttendances()
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $meetings = StudentAttendance::join('meetings', 'meetings.id', '=', 'student_attendances.meeting_id')
        ->join('materials', 'materials.id', '=', 'meetings.material_id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->where('student_attendances.student_id', $student->id)
        ->get(['materials.course_id', 'courses.name', 'student_attendances.meeting_id', 'student_attendances.type',]);
        return response()->json([
			'status' => 200,
			'meetings' => $meetings,
		], 200);
    }
}
