<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'particulars',
        'status',
        'ordered_on',
        'delivered_on',
        'action_taken_by'
    ];

    public function actionTaker()
    {
        return $this->belongsTo(User::class, 'action_taken_by');
    }
}
