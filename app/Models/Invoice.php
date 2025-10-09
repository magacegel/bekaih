<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'report_id',
        'invoice_number',
        'payment_url',
        'session_id', 
        'amount',
        'status',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}