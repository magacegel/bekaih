<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'equipment_certificates';

    protected $casts = [
        'metadata' => 'array',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
