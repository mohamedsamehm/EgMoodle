<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialSection extends Model
{
    use HasFactory;
    protected $fillable = [
        'section_id',
        'material_id',
    ];
    public function section()
    {
        return $this->belongsTo('App\Models\Section', 'section_id');
    }
    public function material() {
        return $this->belongsTo('App\Models\Material', 'material_id');
    }
}
