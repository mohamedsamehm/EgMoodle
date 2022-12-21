<?php

namespace App\Http\Controllers;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Material;
use Auth;
use Illuminate\Http\Request;
class EnrollmentController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function show(Request $request)
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $courses = Enrollment::where('student_id', $student->id)
        ->join('sections', 'sections.id', '=', 'enrollments.section_id')
        ->join('courses', 'courses.id', '=', 'enrollments.course_id')
        ->get(['courses.name as course_name', 'course_id', 'sections.name', 'enrollments.*', 'courses.image'])->unique();

        $materials = Material::join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('enrollments', 'enrollments.section_id', '=', 'material_sections.section_id')
        ->whereColumn('enrollments.course_id', 'materials.course_id')
        ->get(['materials.*'])->unique();

        $student_materials = Material::join('student_materials', 'student_materials.material_id', '=', 'materials.id')
        ->where('student_materials.student_id', $student->id)
        ->where('student_materials.read', '1')
        ->get();
        return response()->json([$courses, $materials, $student_materials]);
    }
}
