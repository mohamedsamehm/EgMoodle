<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Material;
use App\Models\MaterialSection;
use App\Models\Student;
use App\Models\StudentAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    public function show(Request $request)
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $assignments = Assignment::where('assignments.id', $request->id)
        ->join('materials', 'materials.id', '=', 'assignments.material_id')
        ->join('users', 'users.id', '=', 'materials.user_id')
        ->join('roles', 'roles.id', '=', 'users.roleId')
        ->get(['assignments.id as assignment_id', 'assignments.*', 'users.fullName', 'roles.slug', 'materials.*', 'assignments.file_name as file_path']);
        
        $student_assignment = StudentAssignment::where('assignment_id', $request->id)
        ->where('student_id', $student->id)->get();
        return response()->json([$assignments, $student_assignment]);
    }
    public function index()
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $assignments = Assignment::join('materials', 'materials.id', '=', 'assignments.material_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('enrollments', 'enrollments.section_id', '=', 'material_sections.section_id')
        ->whereColumn('enrollments.course_id', 'materials.course_id')
        ->join('users', 'users.id', '=', 'materials.user_id')
        ->join('roles', 'roles.id', '=', 'users.roleId')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->where('enrollments.student_id', $student->id)
        ->get(['assignments.*', 'users.fullName', 'roles.slug', 'materials.*', 'assignments.file_name as file_path', 'assignments.id as assignment_id', 'courses.name as course_name']);
        
        $student_assignment = StudentAssignment::where('student_id', $student->id)->get();
        return response()->json([$assignments, $student_assignment]);
    }
    public function getAssignmentDetails(Request $request){
        $assignment = Assignment::join('materials', 'materials.id', '=', 'assignments.material_id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->where('assignments.id', $request->id)
        ->get([
            'assignments.id as assignment_id', 
            'materials.title', 
            'materials.content',
            'materials.week', 
            'assignments.file_name as file_path',
            'courses.id as course_id',
            'courses.name as course_name',
            'assignments.deadline as deadline',
            'materials.id as material_id'
        ])->first();
        $sections = Assignment::join('materials', 'materials.id', '=', 'assignments.material_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('sections', 'sections.id', '=', 'material_sections.section_id')
        ->where('assignments.id', $request->id)
        ->get([
            'sections.id as section_id',
            'sections.name as section_name',
        ]);
        return response()->json([
			'status' => 200,
			'assignment' => $assignment,
			'sections' => $sections,
		], 200);
	}
    public function all()
    {
        $assignments = Assignment::join('materials', 'materials.id', '=', 'assignments.material_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('sections', 'sections.id', '=', 'material_sections.section_id')
        ->join('users', 'users.id', '=', 'materials.user_id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->where('materials.user_id', Auth::user()->id)
        ->get(['materials.*', 'assignments.file_name as file_path', 'assignments.id as assignment_id', 'assignments.*', 'courses.*', 'users.*', 'courses.name as course_name', 'assignments.created_at as createdDate', 'sections.name as section_name', 'sections.id as section_id']);
        return response()->json([$assignments]);
    }
    public function indexProfessor(Request $request)
    {
        $assignments = Assignment::join('materials', 'materials.id', '=', 'assignments.material_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('sections', 'sections.id', '=', 'material_sections.section_id')
        ->join('users', 'users.id', '=', 'materials.user_id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->where('materials.user_id', Auth::user()->id)
        ->where('materials.title', 'like',"%{$request->q}%")
        ->paginate(15, ['materials.*', 'assignments.file_name as file_path', 'assignments.id as assignment_id', 'assignments.*', 'courses.*', 'users.*', 'courses.name as course_name', 'assignments.created_at as createdDate', 'sections.name as section_name', 'sections.id as section_id']);
        return response()->json([$assignments]);
    }
    public function store(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:jpg,jpeg,png,txt,xlx,xls,csv,pdf,pptx,doc,docx|max:2048',
            'deadline' => 'required',
            'sections' => 'required',
            'course_id' => 'required',
            'content' => 'required',
            'title' => 'required',
            'week' => 'required',
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
            $materials = Material::create([
                'course_id' => $request->course_id,
                'content' =>  $request->content,
                'title' =>  $request->title,
                'isAssignment' =>  1,
                'isExam' =>  0,
                'isContent' =>  0,
                'week' =>  $request->week,
                'user_id' => Auth::user()->id
            ]);
            foreach (explode(',', $request->sections[0]) as $key => $value) {
                $material_sections = MaterialSection::create([
                    'material_id' => $materials->id,
                    'section_id' => $value
                ]);
            }
            $assignments = Assignment::create([
                'deadline' => $request->deadline,
                'file_name' =>  $path,
                'material_id' =>  $materials->id,
            ]);
            $savedfile = Assignment::latest()->firstOrFail();
            return response()->json(['status' => 200, $savedfile]);
        }
    }
    public function update(Request $request)
    {
        $rules = [
            'file' => 'mimes:jpg,jpeg,png,txt,xlx,xls,csv,pdf,pptx,doc,docx|max:2048',
            'deadline' => 'required',
            'sections' => 'required',
            'course_id' => 'required',
            'content' => 'required',
            'title' => 'required',
            'week' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 401,
                'errors' => $validator->errors(),
                'request' => $request->material_id,
            ], 200);				
        }
        $materials = Material::find($request->material_id);
        $materials->course_id = $request->course_id;
        $materials->content = $request->content;
        $materials->title = $request->title;
        $materials->week = $request->week;
        $materials->save();

        if($request->sections) {
            $material_sections = MaterialSection::where('material_id', $request->material_id);
            $material_sections->delete();
            
            foreach (explode(',', $request->sections[0]) as $key => $value) {
                $material_sections = MaterialSection::create([
                    'material_id' =>  $request->material_id,
                    'section_id' => $value
                ]);
            }
        }
        $assignments = Assignment::find($request->assignment_id);
        $assignments->deadline = $request->deadline;
        if($request->file()) {
            $file_name = time().'_'.$request->file->getClientOriginalName();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
            $path = '/storage/' . $file_path;
            $assignments->file_name = $path;
        }
        $assignments->save();
        $savedfile = Assignment::latest()->firstOrFail();
        return response()->json(['status' => 200, $savedfile]);
    }
}
