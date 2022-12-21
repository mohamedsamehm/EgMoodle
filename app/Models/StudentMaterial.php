<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMaterial extends Model
{
    use HasFactory;
    protected $fillable = [
        'read',
        'student_id',
        'material_id',
    ];
    public function student() {
        return $this->belongsTo('App\Models\Student', 'student_id');
    }
    public function material() {
        return $this->belongsTo('App\Models\Material', 'material_id');
    }
}