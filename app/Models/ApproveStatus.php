<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproveStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'approve_status_th',
    ];
}