<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'group',
        'max_capicty',
        'semster_id',
        'level_id',
        'department_id'
    ];

    public function semester() {
        return $this->belongsTo('App\Models\Semester', 'semster_id');
    }
    public function level() {
        return $this->belongsTo('App\Models\Level', 'level_id');
    }
    public function department() {
        return $this->belongsTo('App\Models\Department', 'department_id');
    }
}
