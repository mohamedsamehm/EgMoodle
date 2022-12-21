<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialSection;
use App\Models\Meeting;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    public function getMeetingDetails(Request $request){
        $meeting = Meeting::join('materials', 'materials.id', '=', 'meetings.material_id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->where('meetings.id', $request->id)
        ->get([
            'meetings.id as meeting_id', 
            'materials.title', 
            'materials.content',
            'materials.week', 
            'materials.teams_url', 
            'courses.id as course_id',
            'courses.name as course_name',
            'materials.id as material_id'
        ])->first();
        $sections = Meeting::join('materials', 'materials.id', '=', 'meetings.material_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('sections', 'sections.id', '=', 'material_sections.section_id')
        ->where('meetings.id', $request->id)
        ->get([
            'sections.id as section_id',
            'sections.name as section_name',
        ]);
        return response()->json([
			'status' => 200,
			'meeting' => $meeting,
			'sections' => $sections,
		], 200);
	}
    public function indexProfessor(Request $request)
    {
        $meetings = Meeting::join('materials', 'materials.id', '=', 'meetings.material_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('sections', 'sections.id', '=', 'material_sections.section_id')
        ->join('users', 'users.id', '=', 'materials.user_id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->where('materials.user_id', Auth::user()->id)
        ->where('materials.title', 'like',"%{$request->q}%")
        ->paginate(15, ['materials.*', 'meetings.id as meeting_id', 'meetings.*', 'courses.*', 'users.*', 'courses.name as course_name', 'meetings.created_at as createdDate', 'sections.name as section_name', 'sections.id as section_id']);
        return response()->json([$meetings]);
    }
    public function viewAttendance(Request $request)
    {
        $meetings = Meeting::join('materials', 'materials.id', '=', 'meetings.material_id')
        ->join('student_attendances', 'student_attendances.meeting_id', '=', 'meetings.id')
        ->join('students', 'students.id', '=', 'student_attendances.student_id')
        ->join('users', 'users.id', '=', 'students.user_id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->where('materials.user_id', Auth::user()->id)
        ->where('materials.course_id', $request->course_id)
        // ->whereColumn('enrollments.course_id', 'materials.course_id')
        ->get(['users.fullName', 'users.email', 'student_attendances.type', 'materials.title', 'meetings.id'])
        ->groupBy(function($data) {
            return $data->id;
        });
        return response()->json([
			'status' => 200,
			'meetings' => $meetings,
		], 200);
    }
    public function viewAllAttendance()
    {
        $meetings = Meeting::join('materials', 'materials.id', '=', 'meetings.material_id')
        ->join('student_attendances', 'student_attendances.meeting_id', '=', 'meetings.id')
        ->join('students', 'students.id', '=', 'student_attendances.student_id')
        ->join('users', 'users.id', '=', 'students.user_id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->where('materials.user_id', Auth::user()->id)
        ->get(['courses.name', 'courses.id as course_id', 'students.id', 'student_attendances.type', 'meetings.id'])
        ->groupBy(function($data) {
            return $data->course_id;
        });
        return response()->json([
			'status' => 200,
			'meetings' => $meetings,
		], 200);
    }
    public function viewStudentAttendance(Request $request)
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $meetings = Meeting::join('materials', 'materials.id', '=', 'meetings.material_id')
        ->join('student_attendances', 'student_attendances.meeting_id', '=', 'meetings.id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->where('student_attendances.student_id', $student->id)
        ->where('materials.course_id', $request->course_id)
        ->get(['materials.title as meeting', 'student_attendances.type as status', 'meetings.created_at as date_created']);
        return response()->json([
			'status' => 200,
			'meetings' => $meetings,
		], 200);
    }
    public function store(Request $request)
    {
        $rules = [
            'sections' => 'required',
            'teams_url' => 'required',
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
        $materials = Material::create([
            'course_id' => $request->course_id,
            'teams_url' => $request->teams_url,
            'content' =>  $request->content,
            'title' =>  $request->title,
            'isAssignment' =>  0,
            'isExam' =>  0,
            'isContent' =>  1,
            'week' =>  $request->week,
            'user_id' => Auth::user()->id
        ]);
        foreach (explode(',', $request->sections[0]) as $key => $value) {
            $material_sections = MaterialSection::create([
                'material_id' => $materials->id,
                'section_id' => $value
            ]);
        }
        $meetings = Meeting::create([
            'material_id' =>  $materials->id,
        ]);
        return response()->json(['status' => 200]);
    }
    public function update(Request $request)
    {
        $rules = [
            'sections' => 'required',
            'teams_url' => 'required',
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
        $materials->teams_url = $request->teams_url;
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
        return response()->json(['status' => 200]);
    }
    public function getAllStudents($id)
    {
        $students = Meeting::join('materials', 'materials.id', '=', 'meetings.material_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('enrollments', 'enrollments.section_id', '=', 'material_sections.section_id')
        ->join('students', 'students.id', '=', 'enrollments.student_id')
        ->join('users', 'users.id', '=', 'students.user_id')
        ->whereColumn('enrollments.course_id', 'materials.course_id')
        ->where('meetings.id', $id)
        ->get(['users.email', 'students.id']);
        return response()->json([
			'status' => 200,
			'students' => $students,
		], 200);
    }
}
