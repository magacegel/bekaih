<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;
    protected $table = 'equipments';

    protected $guarded = ['created_at', 'updated_at'];

    protected $fillable = ['name', 'manufactur', 'model', 'serial', 'tolerancy', 'probe_type', 'company_id'];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }   

    public function activeCertifications()
    {
        return $this->hasMany(Certification::class)->where('active', 1);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }
}
