<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Material;
use App\Models\MaterialSection;
use Illuminate\Http\Request;
use App\Models\Professor;
use App\Models\CourseSection;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function show(Request $request)
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $materials = Material::join('users', 'users.id', '=', 'materials.user_id')
        ->join('roles', 'roles.id', '=', 'users.roleId')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('enrollments', 'enrollments.section_id', '=', 'material_sections.section_id')
        ->whereColumn('enrollments.course_id', 'materials.course_id')
        ->where('materials.course_id', $request->id)
        ->where('enrollments.student_id', $student->id)
        ->get(['materials.*', 'users.fullName as user_name', 'roles.slug'])->groupBy(function($data) {
            return $data->id;
        });

        $assignments = Material::join('users', 'users.id', '=', 'materials.user_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('assignments', 'assignments.material_id', '=', 'materials.id')
        ->join('enrollments', 'enrollments.section_id', '=', 'material_sections.section_id')
        ->whereColumn('enrollments.course_id', 'materials.course_id')
        ->where('materials.course_id', $request->id)
        ->where('enrollments.student_id', $student->id)
        ->get(['assignments.id as assignment_id', 'materials.*', 'material_sections.*', 'users.*', 'assignments.file_name as file_name'])->groupBy(function($data) {
            return $data->material_id;
        });

        $student_assignments = Material::join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('enrollments', 'enrollments.section_id', '=', 'material_sections.section_id')
        ->whereColumn('enrollments.course_id', 'materials.course_id')
        ->join('assignments', 'assignments.material_id', '=', 'materials.id')
        ->join('student_assignments', 'student_assignments.assignment_id', '=', 'assignments.id')
        ->where('student_assignments.student_id', $student->id)
        ->where('enrollments.course_id', $request->id)
        ->get();

        $courses = CourseSection::join('enrollments', 'enrollments.section_id', '=', 'course_sections.section_id')
        ->whereColumn('enrollments.course_id', 'course_sections.course_id')
        ->join('sections', 'sections.id', '=', 'course_sections.section_id')
        ->join('courses', 'courses.id', '=', 'course_sections.course_id')
        ->where('enrollments.course_id', $request->id)
        ->get(['courses.name as course_name', 'sections.name as section_name', 'courses.*', 'courses.id as course_id']);

        $quizzes = Material::join('users', 'users.id', '=', 'materials.user_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('exams', 'exams.material_id', '=', 'materials.id')
        ->join('enrollments', 'enrollments.section_id', '=', 'material_sections.section_id')
        ->whereColumn('enrollments.course_id', 'materials.course_id')
        ->where('materials.course_id', $request->id)
        ->where('enrollments.student_id', $student->id)
        ->get(['exams.id as exam_id', 'materials.*', 'material_sections.*', 'users.*', 'exams.*'])->groupBy(function($data) {
            return $data->material_id;
        });

        $student__quizzes = Material::join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('enrollments', 'enrollments.section_id', '=', 'material_sections.section_id')
        ->whereColumn('enrollments.course_id', 'materials.course_id')
        ->join('exams', 'exams.material_id', '=', 'materials.id')
        ->join('student_exams', 'student_exams.exam_id', '=', 'exams.id')
        ->where('student_exams.student_id', $student->id)
        ->where('enrollments.course_id', $request->id)
        ->get();
        
        return response()->json([$materials, $courses, $assignments, $student_assignments, $quizzes, $student__quizzes]);
    }
    public function show_professor(Request $request)
    {
        $professor = Professor::where('user_id', Auth::user()->id)->first();
        $materials = Material::join('users', 'users.id', '=', 'materials.user_id')
        ->join('roles', 'roles.id', '=', 'users.roleId')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('lectures', 'lectures.section_id', '=', 'material_sections.section_id')
        ->whereColumn('lectures.course_id', 'materials.course_id')
        ->where('materials.course_id', $request->id)
        ->where('lectures.professor_id', $professor->id)
        ->get(['materials.*', 'users.fullName as user_name', 'roles.slug'])->groupBy(function($data) {
            return $data->id;
        });

        $materials_sections = MaterialSection::join('materials', 'materials.id', '=', 'material_sections.material_id')
        ->join('users', 'users.id', '=', 'materials.user_id')
        ->join('roles', 'roles.id', '=', 'users.roleId')
        ->join('sections', 'sections.id', '=', 'material_sections.section_id')
        ->join('lectures', 'lectures.section_id', '=', 'material_sections.section_id')
        ->whereColumn('lectures.course_id', 'materials.course_id')
        ->where('materials.course_id', $request->id)
        ->where('lectures.professor_id', $professor->id)
        ->get(['material_sections.*', 'users.fullName as user_name', 'roles.slug', 'sections.name as section_name'])->groupBy(function($data) {
            return $data->material_id;
        });

        $assignments = Material::join('users', 'users.id', '=', 'materials.user_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('assignments', 'assignments.material_id', '=', 'materials.id')
        ->join('lectures', 'lectures.section_id', '=', 'material_sections.section_id')
        ->whereColumn('lectures.course_id', 'materials.course_id')
        ->where('materials.course_id', $request->id)
        ->where('lectures.professor_id', $professor->id)
        ->get()->groupBy(function($data) {
            return $data->material_id;
        });
        $quizzes = Material::join('users', 'users.id', '=', 'materials.user_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('exams', 'exams.material_id', '=', 'materials.id')
        ->join('lectures', 'lectures.section_id', '=', 'material_sections.section_id')
        ->whereColumn('lectures.course_id', 'materials.course_id')
        ->where('materials.course_id', $request->id)
        ->where('lectures.professor_id', $professor->id)
        ->get(['exams.id as exam_id', 'materials.*', 'material_sections.*', 'users.*', 'exams.*'])->groupBy(function($data) {
            return $data->material_id;
        });
        $courses = CourseSection::join('lectures', 'lectures.section_id', '=', 'course_sections.section_id')
        ->whereColumn('lectures.course_id', 'course_sections.course_id')
        ->join('sections', 'sections.id', '=', 'course_sections.section_id')
        ->join('courses', 'courses.id', '=', 'course_sections.course_id')
        ->where('lectures.course_id', $request->id)
        ->where('lectures.professor_id', $professor->id)
        ->get(['courses.name as course_name', 'sections.name as section_name', 'courses.*', 'courses.id as course_id']);
        return response()->json([$materials, $materials_sections, $assignments, $courses, $quizzes]);
    }
    public function destroy(Request $request)
    {
        try {
            Material::where('id', $request->id)->delete();
            return response()->json([
                'status' => 200,
                'msg' => 'Material deleted successfully',
            ], 200);	
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}