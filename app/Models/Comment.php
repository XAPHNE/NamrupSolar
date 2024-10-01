<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'drawing_file_id',  // Make sure this matches the column name in the migration
        'comment_body',
        'commented_at',
        'commented_by',
        'resubmitted_at',
    ];

    public function drawingFile()
    {
        return $this->belongsTo(DrawingFile::class, 'drawing_file_id');
    }

    public function commenter()
    {
        return $this->belongsTo(User::class, 'commented_by');
    }
}
