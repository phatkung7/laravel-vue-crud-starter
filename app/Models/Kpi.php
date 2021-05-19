<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;
    protected $fillable = [
        'kpi_no',
        'kpi_title',
        'approve_status_id',
        'budget_year'
    ];

    public function ApproveStatus()
    {
        return $this->belongsTo(ApproveStatus::class);
    }

}
