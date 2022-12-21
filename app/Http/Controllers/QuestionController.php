<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentExam;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $exam = Exam::where('id', $request->exam_id)->first();
        $questions = Question::where('questions.exam_id', $request->exam_id)
        ->join('options', 'options.question_id', '=', 'questions.id')
        ->inRandomOrder()
        ->get(['questions.*', 'options.*', 'questions.created_at as createdDate', 'options.id as option_id'])->groupBy(function($data) {
            return $data->question_id;
        });
        $student = Student::where('user_id', $request->student_id)->first();
        $student__questions = StudentExam::join('exams', 'exams.id', '=', 'student_exams.exam_id')
        ->join('questions', 'questions.exam_id', '=', 'exams.id')
        ->join('options', 'options.question_id', '=', 'questions.id')
        ->join('student__answers', 'student__answers.answer_id', '=', 'options.id')
        ->whereColumn('student__answers.student_exam_id', 'student_exams.id')
        ->where('student_exams.exam_id', $request->exam_id)
        ->where('student_id', $student->id)
        ->get(['questions.*', 'options.*', 'questions.created_at as createdDate', 'options.id as option_id', 'student_exams.created_at as time_submitting', 'student_exams.grade as grade'])->groupBy(function($data) {
            return $data->question_id;
        });
        return response()->json([$questions, $student__questions, $exam]);
    }
    public function show(Request $request)
    {
        $questions = Question::where('questions.exam_id', $request->exam_id)
        ->join('options', 'options.question_id', '=', 'questions.id')
        ->get(['questions.*', 'options.*', 'questions.created_at as createdDate', 'options.id as option_id'])->groupBy(function($data) {
            return $data->question_id;
        });
        return response()->json($questions);
    }
    public function destroy(Request $request)
    {
        $questions = Question::find($request->question_id);
        $questions->delete();
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'type' => 'required',
            'exam_id' => 'required',
            'optionsObj' => 'required',
        ]);
        $questions = Question::create([
            'title' => $request->title,
            'type' =>  $request->type,
            'exam_id' =>  $request->exam_id,
        ]);
        foreach ($request->optionsObj as $key => $value) {
            if( $value['value'] !== null ||  $value['value'] !== '') {
                $choices = Option::create([
                    'option' => $value['value'],
                    'answer' => $value['answer'],
                    'question_id' =>  $questions->id,
                ]);
            }
        }
    }
    public function update(Request $request)
    {
        $questions = Question::find($request->question_id);
        $this->validate($request, [
            'title' => 'required',
            'type' => 'required',
            'optionsObj' => 'required',
        ]);
        $questions->title = $request->title;
        $questions->type = $request->type;
        $questions->save();

        if($request->optionsObj) {
            $choices = Option::where('question_id', $request->question_id);
            $choices->delete();
            
            foreach ($request->optionsObj as $key => $value) {
                $choices = Option::create([
                    'option' => $value['value'],
                    'answer' => $value['answer'],
                    'question_id' =>  $request->question_id,
                ]);
            }
        }
    }
}
