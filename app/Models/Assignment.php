<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'deadline',
        'file_name',
        'material_id',
    ];
    public function material() {
        return $this->belongsTo('App\Models\Material', 'material_id');
    }
}
