<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\Professor;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LectureController extends Controller
{
    public function show()
    {
        $professor = Professor::where('user_id', Auth::user()->id)->first();
        $courses = Lecture::where('professor_id', $professor->id)
        ->join('sections', 'sections.id', '=', 'lectures.section_id')
        ->join('courses', 'courses.id', '=', 'lectures.course_id')
        ->get(['courses.name as course_name', 'course_id', 'sections.name as section_name', 'lectures.*', 'courses.image']);

        $materials = Material::join('material_sections', 'material_sections.material_id', '=', 'materials.id')
        ->join('lectures', 'lectures.section_id', '=', 'material_sections.section_id')
        ->whereColumn('lectures.course_id', 'materials.course_id')
        ->get(['materials.*']);
        
        return response()->json([$courses, $materials]);
    }
    public function showLectures($id)
    {
        $professor = Professor::where('user_id', Auth::user()->id)->first();
        $courses = Lecture::where('professor_id', $professor->id)
        ->join('sections', 'sections.id', '=', 'lectures.section_id')
        ->join('courses', 'courses.id', '=', 'lectures.course_id')
        ->where('lectures.course_id', $id)
        ->get(['courses.name as course_name', 'course_id', 'sections.name as section_name', 'sections.id as section_id', 'lectures.*']);
        return response()->json([$courses]);
    }
}
