<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $fillable = ['name', 'brand', 'address', 'city','zip_code'];

    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'id');
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class, 'company_id', 'id');
    }

    public function certificates()
    {
        return $this->hasMany(CompanyCertificate::class, 'company_id', 'id');
    }

    public function activeCertificate()
    {
        return $this->belongsTo(CompanyCertificate::class, 'company_certificate_id');
    }
}
