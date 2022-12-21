<?php

namespace App\Http\Controllers;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Models\Student;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->role == "admin") {
            return redirect()->route('admin.students');
        }
        $student = Student::where('user_id', Auth::id())->first();
        $courses = Enrollment::where('student_id', $student->id)->get();
        return view('student.home')->with('courses', $courses);
    }
}
