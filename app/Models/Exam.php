<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'opens_at',
        'duration',
        'material_id',
    ];
    public function material() {
        return $this->belongsTo('App\Models\Material', 'material_id');
    }
}
