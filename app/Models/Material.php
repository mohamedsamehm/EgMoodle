<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'user_id',
        'teams_url',
        'content',
        'title',
        'isAssignment',
        'isExam',
        'isContent',
        'week',
    ];
    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }
    public function section()
    {
        return $this->belongsTo('App\Models\Section', 'section_id');
    }
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
