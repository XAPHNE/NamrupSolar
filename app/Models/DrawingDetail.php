<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DrawingDetail extends Model
{
    use HasFactory, SoftDeletes;

    public function drawing()
    {
        return $this->belongsTo(Drawing::class, 'drawing_id');
    }
}
