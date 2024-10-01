<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MajorActivity extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = ['name', 'image_path', 'scope', 'completed', 'unit'];
}
