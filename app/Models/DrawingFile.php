<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DrawingFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['drawing_file_id', 'file_path'];

    public function drawingDetail()
    {
        return $this->belongsTo(DrawingDetail::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'drawing_file_id');
    }
}
