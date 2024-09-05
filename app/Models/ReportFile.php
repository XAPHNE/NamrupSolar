<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['drawing_detail_id', 'file_path'];

    public function drawingDetail()
    {
        return $this->belongsTo(DrawingDetail::class);
    }
}
