<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $semesters = Semester::all();
        return view('admin.semester')->with('semesters', $semesters);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
        ]);
        $semester = Semester::create([
            'name' => $request->name,
            'date_start' => $request->date_start,
            'date_end'=> $request->date_end,
            'level_id'=> '1',
            'active' => '1',
        ]);
    }
    public function update(Request $request, $id)
    {
        $semester = Semester::find($id);
        $this->validate($request, [
            'name' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
        ]);
        $semester->name = $request->name;
        $semester->date_start = $request->date_start;
        $semester->date_end = $request->date_end;
        $saved = $semester->save();
    }
    public function destroy($id)
    {
        $semester = Semester::find($id);
        $semester->delete();
    }
}
