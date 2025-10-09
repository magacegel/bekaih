<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportHistory extends Model
{
    protected $fillable = [
        'report_id',
        'user_id',
        'status',
        'actor_type',
        'notes'
    ];

    // Bisa tambahkan konstanta untuk memudahkan penggunaan
    const ACTOR_SUPERVISOR = 'supervisor';
    const ACTOR_SURVEYOR = 'surveyor';
    const ACTOR_OPERATOR = 'operator';

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 