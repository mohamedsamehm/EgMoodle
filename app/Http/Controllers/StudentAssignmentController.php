<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StudentAssignmentController extends Controller
{
    public function store(Request $request)
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $rules = [
            'assignment_id' => 'required',
            'file' => 'required|mimes:jpg,jpeg,png,txt,xlx,xls,csv,pdf,pptx,doc,docx|max:2048',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 401,
                'errors' => $validator->errors(),
            ], 200);				
        }
        if($request->file()) {
            $file_name = time().'_'.$request->file->getClientOriginalName();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
            $path = '/storage/' . $file_path;
            $student_assignments = StudentAssignment::create([
                'student_id' => $student->id,
                'assignment_id'  => $request->assignment_id,
                'path' =>  $path,
                'name' => $request->file->getClientOriginalName(),
                'grade' => ''
            ]);
            $savedfile = StudentAssignment::latest()->firstOrFail();
            return response()->json(['status' => 200, $savedfile]);
        }
    }
    public function show(Request $request)
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $student_assignment = StudentAssignment::where('assignment_id', $request->assignment_id)
        ->where('student_id', $student->id)->get();
        return response()->json($student_assignment);
    }
    public function update(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:jpg,jpeg,png,txt,xlx,xls,csv,pdf,pptx,doc,docx|max:2048',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 401,
                'errors' => $validator->errors(),
            ], 200);				
        }
        if($request->file()) {
            $file_name = time().'_'.$request->file->getClientOriginalName();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
            $path = '/storage/' . $file_path;

            $student_assignments = StudentAssignment::find($request->id);
            $student_assignments->path = $path;
            $student_assignments->name = $request->file->getClientOriginalName();
            $student_assignments->save();
        }
        $savedfile = StudentAssignment::latest()->firstOrFail();
        return response()->json(['status' => 200, $savedfile]);
    }
    public function showAssignments($id)
    {
        // ->whereColumn('enrollments.course_id', 'schedules.course_id')
        $student_assignments = StudentAssignment::join('students', 'students.id', '=', 'student_assignments.student_id')
        ->join('users', 'users.id', '=', 'students.user_id')
        ->join('assignments', 'assignments.id', '=', 'student_assignments.assignment_id')
        ->join('materials', 'materials.id', '=', 'assignments.material_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('sections', 'sections.id', '=', 'material_sections.section_id')
        ->join('enrollments', 'enrollments.course_id', '=', 'materials.course_id')
        ->whereColumn('material_sections.section_id', 'enrollments.section_id')
        ->where('assignments.id', $id)
        ->get([
            'student_assignments.id as student_assignment_id',
            'student_assignments.*',
            'users.fullName',
            'sections.name as section_name',
            'users.email',
            'materials.title',
            'materials.course_id'
        ])->unique();
        return response()->json([$student_assignments]);
    }
    public function saveGrade(Request $request)
    {
        $student_assignments = StudentAssignment::find($request->id);
        $this->validate($request, [
            'id' => 'required',
            'grade' => 'required',
        ]);
        $student_assignments->id = $request->id;
        $student_assignments->grade = $request->grade;
        $saved = $student_assignments->save();
        return response()->json(['status'=> 200]);
    }
}
