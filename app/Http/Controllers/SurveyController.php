<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'questions' => 'required',
            'course_id' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 401,
                'errors' => $validator->errors(),
            ], 200);
        }
        $student = Student::where('user_id', Auth::id())->first();
        $survey = Survey::create([
            'course_id' => $request->course_id,
            'student_id' => $student->id,
            'questions' => json_encode($request->questions)
        ]);
        return response()->json([
            'status' => 200,
            'survey' => $survey
        ], 200);
    }

    public function show(Request $request)
    {
        $student = Student::where('user_id', Auth::id())->first();
        $survey = Survey::where('student_id', $student->id)->where('course_id', $request->id)->get();
        return response()->json([$survey]);
    }
}
