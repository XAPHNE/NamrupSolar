<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'drawing_details_id',
        'commented_at',
        'commented_by',
        'comment_body',
        'resubmitted_at',
    ];

    public function drawingDetail()
    {
        return $this->belongsTo(DrawingDetail::class);
    }

    public function commenter()
    {
        return $this->belongsTo(User::class, 'commented_by');
    }
}
