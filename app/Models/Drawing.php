<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Drawing extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = ['name', 'drawing_no', 'total_drawings', 'total_drawings_scope', 'total_submitted_drawings', 'total_approved_drawings'];
}
