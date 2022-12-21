<?php

namespace App\Http\Controllers;

use App\Models\StudentCourse;
use App\Models\Course;
use App\Models\Student;
use Auth;
use Illuminate\Http\Request;

class StudentCourseController extends Controller
{
    public function index()
    {
        $student = Student::where('user_id', Auth::id())->first();
        $student__courses = StudentCourse::where('student_id', $student->id)->get();
        return view("student.courses", ["courses" => $student__courses, "student" => $student]);
    }
    public function showfinishedcourses($id)
    {
        $course = Course::where("id", $id)->first();
        if($course->Pre_Req) {
            $courses = StudentCourse::where('course_id', $course->Pre_Req)->get(['course_id']);
            $courseName = Course::where("id", $course->Pre_Req)->get(['name']);
            return response()->json([$courses, $courseName]);
        }
        return response()->json(['null']);
    }
}
