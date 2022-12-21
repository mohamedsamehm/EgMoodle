<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentExam;
use App\Models\StudentAnswers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentExamController extends Controller
{
    public function store(Request $request)
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $this->validate($request, [
            'grade' => 'required',
            'exam_id' => 'required',
            'questions' => 'required'
        ]);
        $student_exams = StudentExam::create([
            'grade' => $request->grade,
            'student_id' =>  $student->id,
            'exam_id' =>  $request->exam_id,
        ]);
        foreach ($request->questions as $question) {
            foreach ($question['my_answer_choice_id'] as $answer) {
                $choices = StudentAnswers::create([
                    'answer_id' => $answer,
                    'student_exam_id' =>  $student_exams->id,
                ]);
            }
        }
        return response()->json([
            'status' => 200,
            'request' => $choices
        ], 200);
    }
}
