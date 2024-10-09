<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'bill_no',
        'description',
        'amount',
        'raised_at',
        'file_path',
        'paid_at',
        'action_taken_by',
    ];

    public function actionTaker()
    {
        return $this->belongsTo(User::class, 'action_taken_by');
    }
}
