<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // public function courses()
    // {
    //     $student = Student::where('user_id', Auth::id())->first();
    //     return view('student.courses')->with('student', $student);
    // }
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $students = Student::all();
        return view('admin.students')->with('students', $students);
    }
}
