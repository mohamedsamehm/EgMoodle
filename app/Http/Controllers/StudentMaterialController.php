<?php

namespace App\Http\Controllers;

use App\Models\StudentMaterial;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class StudentMaterialController extends Controller
{
    public function show(Request $request)
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $materials = StudentMaterial::where('material_id', $request->material_id)
        ->where('student_id', $student->id)->get();
        return response()->json($materials);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'material_id' => 'required',
        ]);
        $student = Student::where('user_id', Auth::user()->id)->first();
        $student_material = StudentMaterial::create([
            'material_id' => $request->material_id,
            'student_id' => $student->id,
            'read' => 0
        ]);
        return response()->json(array('success' => true, 'last_insert_id' => $student_material->id), 200);
    }
    public function update(Request $request)
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $student_material = StudentMaterial::find($request->id);
        $this->validate($request, [
            'material_id' => 'required',
            'read' => 'required',
        ]);
        $student_material->material_id = $request->material_id;
        $student_material->student_id = $student->id;
        $student_material->read = $request->read;
        $saved = $student_material->save();
    }
}
