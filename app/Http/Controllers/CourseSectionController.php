<?php

namespace App\Http\Controllers;

use App\Models\CourseSection;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\Request;

class CourseSectionController extends Controller
{   
    // public function coursesection(Request $request)
    // {   
        // $course_id = $request->course_id;
        // $sections = course_section::join('sections', 'sections.id', '=', 'course_sections.section_id')
        // ->where('course_sections.course_id', $course_id)
        // ->get(['sections.*']);
        // return response()->json($sections);
    // }
    public function index()
    {
        $courses = CourseSection::all();
        $all_courses = Course::all();
        $all_sections = Section::all();
        return view("admin.courses_sections", ["courses" => $courses, "all_courses" => $all_courses, "all_sections" => $all_sections]);
    }
    public function showsections($id)
    {
        $sections = CourseSection::join('sections', 'sections.id', '=', 'course_sections.section_id')
        ->where('course_sections.course_id', $id)->get(['sections.*']);
        return response()->json($sections);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'course_id' => 'required',
            'section_id' => 'required',
        ]);
        $course_section = CourseSection::create([
            'course_id' => $request->course_id,
            'section_id' =>  $request->section_id,
        ]);
    }
    public function update(Request $request, $id)
    {
        $course_section = CourseSection::find($id);
        $this->validate($request, [
            'course_id' => 'required',
            'section_id' => 'required',
        ]);
        $course_section->course_id = $request->course_id;
        $course_section->section_id = $request->section_id;
        $course_section->save();
    }
    public function destroy($id)
    {
        $course_section = CourseSection::find($id);
        $course_section->delete();
    }
}
