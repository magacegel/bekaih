<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    use HasFactory;

    protected $table = 'user_competencies';

    protected $fillable = [
        'user_id',
        'qualification',
        'certificate_number',
        'certificate_file',
        'expired_date'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
