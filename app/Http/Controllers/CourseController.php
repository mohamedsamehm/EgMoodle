<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('admin.courses')->with('courses', $courses);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required',
            'credit_hour' => 'required',
            'Regulation' => 'required',
        ]);
        $course = Course::create([
            'id' => $request->id,
            'name'  => $request->name,
            'credit_hour' => $request->credit_hour,
            'Regulation' => $request->Regulation,
            'Pre_Req'  => $request->Pre_Req,
        ]);
    }
    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required',
            'credit_hour' => 'required',
            'Regulation' => 'required',
        ]);
        $course->id = $request->id;
        $course->name = $request->name;
        $course->credit_hour = $request->credit_hour;
        $course->Regulation = $request->Regulation;
        $course->Pre_Req = $request->Pre_Req;
        $course->save();
    }
    public function destroy($id)
    {
        $course = Course::find($id);
        $course->delete();
    }
}
