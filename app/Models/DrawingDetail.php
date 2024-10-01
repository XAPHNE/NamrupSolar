<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DrawingDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'drawing_id',
        'drawing_details_name',
        'drawing_details_no',
        'isScopeDrawing',
        'submitted_at',
        'submitted_by',
        'approved_at',
        'approved_by',
    ];

    public function drawing()
    {
        return $this->belongsTo(Drawing::class);
    }

    public function drawingFiles()
    {
        return $this->hasMany(DrawingFile::class);
    }

    public function reportFiles()
    {
        return $this->hasMany(ReportFile::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
