<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\StudentExam;
use App\Models\StudentAnswers;
use App\Models\Student;
use App\Models\Material;
use App\Models\MaterialSection;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function indexStudent()
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $quizzes = Exam::join('materials', 'materials.id', '=', 'exams.material_id')
        ->join('users', 'users.id', '=', 'materials.user_id')
        ->join('roles', 'roles.id', '=', 'users.roleId')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('enrollments', 'enrollments.section_id', '=', 'material_sections.section_id')
        ->whereColumn('enrollments.course_id', 'materials.course_id')
        ->where('enrollments.student_id', $student->id)
        ->get(['exams.*', 'users.fullName', 'roles.slug', 'materials.*', 'exams.id as exam_id', 'courses.name as course_name'])->unique();

        $student_quizzes = StudentExam::where('student_id', $student->id)->get();
        return response()->json([$quizzes, $student_quizzes]);
    }
    public function getQuizDetailsStudent(Request $request){
        $student = Student::where('user_id', Auth::user()->id)->first();
        $quiz = Exam::join('materials', 'materials.id', '=', 'exams.material_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('enrollments', 'enrollments.section_id', '=', 'material_sections.section_id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->where('enrollments.student_id', $student->id)
        ->where('exams.id', $request->id)
        ->get([
            'exams.id as exam_id', 
            'exams.*',
            'materials.title', 
            'materials.content',
            'materials.week', 
            'courses.id as course_id',
            'courses.name as course_name',
            'materials.id as material_id'
        ])->first();
        $questions = Question::where('questions.exam_id', $request->id)->get(['questions.*']);;
        $options = Question::where('questions.exam_id', $request->id)
        ->join('options', 'options.question_id', '=', 'questions.id')
        ->get(['options.*', 'options.id as option_id']);
        $student_quizzes = StudentExam::where('student_id', $student->id)->get();
        return response()->json([
			'status' => 200,
			'quiz' => $quiz,
			'questions' => $questions,
			'options' => $options,
			'student_quizzes' => $student_quizzes,
		], 200);
    }
    public function getQuizDetailsStudentResults(Request $request){
        $student = Student::where('user_id', Auth::user()->id)->first();
        $quiz = Exam::join('materials', 'materials.id', '=', 'exams.material_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('enrollments', 'enrollments.section_id', '=', 'material_sections.section_id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->join('student_exams', 'student_exams.exam_id', '=', 'exams.id')
        ->where('enrollments.student_id', $student->id)
        ->where('exams.id', $request->id)
        ->get([
            'student_exams.grade',
            'student_exams.created_at as createdDate',
            'materials.title', 
        ])->first();

        $questions = Question::where('questions.exam_id', $request->id)->get(['questions.*']);
        
        $options = Question::join('options', 'options.question_id', '=', 'questions.id')
        ->where('questions.exam_id', $request->id)
        ->get(['options.*', 'options.id as option_id']);

        $answers = StudentAnswers::join('student_exams', 'student_exams.id', '=', 'student_answers.student_exam_id')
        ->where('student_exams.exam_id', $request->id)
        ->get(['student_answers.answer_id']);

        return response()->json([
            'status' => 200,
            'quiz' => $quiz,
            'questions' => $questions,
            'options' => $options,
            'answers' => $answers,
        ], 200);
    }
    public function getQuizDetails(Request $request){
        $quiz = Exam::join('materials', 'materials.id', '=', 'exams.material_id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->where('exams.id', $request->id)
        ->get([
            'exams.id as exam_id', 
            'exams.*',
            'materials.title', 
            'materials.content',
            'materials.week', 
            'courses.id as course_id',
            'courses.name as course_name',
            'materials.id as material_id'
        ])->first();
        $sections = Exam::join('materials', 'materials.id', '=', 'exams.material_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('sections', 'sections.id', '=', 'material_sections.section_id')
        ->where('exams.id', $request->id)
        ->get([
            'sections.id as section_id',
            'sections.name as section_name',
        ]);
        $questions = Question::where('questions.exam_id', $request->id)->get(['questions.*']);;
        $options = Question::where('questions.exam_id', $request->id)
        ->join('options', 'options.question_id', '=', 'questions.id')
        // ->whereColumn('options.question_id', 'questions.id')
        ->get(['options.*', 'options.id as option_id']);
        return response()->json([
			'status' => 200,
			'quiz' => $quiz,
			'sections' => $sections,
			'questions' => $questions,
			'options' => $options,
		], 200);
	}
    public function indexProfessor()
    {
        $quizzes = Exam::join('materials', 'materials.id', '=', 'exams.material_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('sections', 'sections.id', '=', 'material_sections.section_id')
        ->join('users', 'users.id', '=', 'materials.user_id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->where('materials.user_id', Auth::user()->id)
        ->get(['materials.*', 'exams.id as exam_id', 'exams.*', 'courses.*', 'users.*', 'courses.name as course_name', 'exams.created_at as createdDate', 'sections.name as section_name', 'sections.id as section_id']);
        return response()->json([$quizzes]);
    }
    public function indexProfessorMarks(Request $request)
    {
        $quizzes = Exam::join('materials', 'materials.id', '=', 'exams.material_id')
        ->join('courses', 'courses.id', '=', 'materials.course_id')
        ->join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('sections', 'sections.id', '=', 'material_sections.section_id')
        ->join('student_exams', 'student_exams.exam_id', '=', 'exams.id')
        ->join('students', 'students.id', '=', 'student_exams.student_id')
        ->join('users', 'users.id', '=', 'students.user_id')
        ->where('materials.user_id', Auth::user()->id)
        ->where('exams.id', $request->id)
        ->get(['materials.title', 'student_exams.grade', 'exams.duration', 'courses.*', 'users.fullName', 'courses.name as course_name', 'student_exams.created_at as createdDate', 'sections.name as section_name', 'sections.id as section_id']);
        return response()->json([$quizzes]);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'duration' => 'required',
            'opens_at' => 'required',
            'course_id' => 'required',
            'content' => 'required',
            'title' => 'required',
            'week' => 'required',
        ]);
        $materials = Material::create([
            'course_id' => $request->course_id,
            'content' =>  $request->content,
            'title' =>  $request->title,
            'isAssignment' =>  0,
            'isExam' =>  1,
            'isContent' =>  0,
            'week' =>  $request->week,
            'user_id' =>  Auth::user()->id,
        ]);
        foreach ($request->sections as $key => $value) {
            $material_sections = MaterialSection::create([
                'material_id' =>  $materials->id,
                'section_id' => $value
            ]);
        }
        $quizzes = Exam::create([
            'duration' =>  $request->duration,
            'opens_at' =>  $request->opens_at,
            'material_id' =>  $materials->id,
        ]);
        foreach ($request->questions as $key => $value) {
            $questions = Question::create([
                'title' => $value['title'],
                'type' =>  $value['type'],
                'exam_id' =>  $quizzes->id,
            ]);
            foreach ($value['choices'] as $key => $value) {
                $choices = Option::create([
                    'option' => $value['choice'],
                    'answer' => $value['answer'],
                    'question_id' =>  $questions->id,
                ]);
            }
        }
        return response()->json(['status' => 200, 'msg' => 'Quiz Added Successfully', $request->questions]);
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'duration' => 'required',
            'opens_at' => 'required',
            'course_id' => 'required',
            'content' => 'required',
            'title' => 'required',
            'week' => 'required',
        ]);
        $materials = Material::find($request->material_id);

        $materials->course_id = $request->course_id;
        $materials->content = $request->content;
        $materials->title = $request->title;
        $materials->week = $request->week;
        $materials->save();
        

        $material_sections = MaterialSection::where('material_id', $request->material_id);
        $material_sections->delete();

        foreach ($request->sections as $key => $value) {
            $material_sections = MaterialSection::create([
                'material_id' =>  $materials->id,
                'section_id' => $value
            ]);
        }
        $quizzes = Exam::find($request->exam_id);
        $quizzes->duration = $request->duration;
        $quizzes->opens_at = $request->opens_at;
        $quizzes->save();
    
        foreach ($request->questions as $key => $value) {
            if(array_key_exists('id', $value)) {
                $question = Question::where('id', $value['id']);
                $question->delete();
            }
            $questions = Question::create([
                'title' => $value['title'],
                'type' =>  $value['type'],
                'exam_id' =>  $quizzes->id,
            ]);
            foreach ($value['choices'] as $key => $value) {
                $choices = Option::create([
                    'option' => $value['choice'],
                    'answer' => $value['answer'],
                    'question_id' =>  $questions->id,
                ]);
            }
        }
        return response()->json(['status' => 200, 'msg' => 'Quiz updated Successfully', $request->questions]);
    }
}
