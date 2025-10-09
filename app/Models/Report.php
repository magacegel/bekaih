<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $guarded = ['created_at','updated_at'];

    protected $fillable = [
        'company_id',
        'user_id',
        'ship_id',
        'name',
        'kind_of_survey',
        'location',
        'province',
        'city',
        'date_of_measurement',
        'end_date_measurement',
        'report_number',
        'report_date',
        'submit_date',
        'surveyor_nup',
        'surveyor_verifikasi',
        'surveyor_date',
        'supervisor_id',
        'supervisor_verifikasi',
        'supervisor_date',
        'status',
        'updated_by',
        'equipment_id'
    ];

    protected $casts = [
        'date_of_measurement' => 'date',
        'end_date_measurement' => 'date',
        'report_date' => 'date',
        'submit_date' => 'date',
        'surveyor_date' => 'datetime',
        'supervisor_date' => 'datetime',
        'metadata' => 'array',
    ];

    public function ship()
    {
      return $this->belongsTo(Ship::class);
    }
    public function form()
    {
      return $this->hasMany(Form::class);
    }
    public function user()
    {
      return $this->belongsTo(User::class);
    }
    public function supervisor()
    {
      return $this->belongsTo(User::class, 'supervisor_id');
    }
    public function surveyor()
    {
      return $this->hasOne(UserList::class, 'nup','surveyor_nup');
    }
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function reportHistory()
    {
        return $this->hasMany(ReportHistory::class);
    }
}
