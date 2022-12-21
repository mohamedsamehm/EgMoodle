<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Level;
use App\Models\Section;
use App\Models\Semester;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $levels = Level::all();
        $semesters = Semester::all();
        $sections = Section::all();
        return view("admin.sections", ["sections" => $sections, 'semesters' => $semesters, 'levels' => $levels, 'departments' => $departments]);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'group' => 'required',
            'max_capicty' => 'required',
            'semster_id' => 'required',
            'level_id' => 'required',
            'department_id' => 'required',
        ]);
        $section = Section::create([
            'name' => $request->name,
            'group' =>  $request->group,
            'max_capicty' =>  $request->max_capicty,
            'semster_id' => $request->semster_id,
            'level_id' => $request->level_id,
            'department_id' => $request->department_id,
        ]);
    }
    public function update(Request $request, $id)
    {
        $section = Section::find($id);
        $this->validate($request, [
            'name' => 'required',
            'group' => 'required',
            'max_capicty' => 'required',
            'semster_id' => 'required',
            'level_id' => 'required',
            'department_id' => 'required',
        ]);
        $section->name = $request->name;
        $section->group =  $request->group;
        $section->max_capicty =  $request->max_capicty;
        $section->semster_id = $request->semster_id;
        $section->level_id = $request->level_id;
        $section->department_id = $request->department_id;
        $section->save();
    }
    public function destroy($id)
    {
        $section = Section::find($id);
        $section->delete();
    }
}
