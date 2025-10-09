<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $guarded = ['created_at','updated_at'];

    // public function form_data()
    // {
    //   return $this->hasMany(FormData::class)->orderBy('plate_position','desc');
    // }

    protected $fillable = [
        'report_id',
        'form_type_id',
        'name',
        'title_3',
        'title_2', 
        'title_1',
        'default_dim_lvl',
        'default_org_thickness',
        'default_min_thickness',
        'total_line',
        'total_spot',
        'order',
        'surveyor_date',
        'surveyor_verifikasi',
        'surveyor_notes',
        'supervisor_date',
        'supervisor_verifikasi', 
        'supervisor_notes'
    ];

    protected $casts = [
        'surveyor_date' => 'datetime',
        'supervisor_date' => 'datetime'
    ];



    public function form_data_one()
    {
        return $this->hasMany(FormDataOne::class)->orderBy('plate_position','desc');
    }
    public function form_data_two()
    {
        return $this->hasMany(FormDataTwo::class);
    }
    public function form_data_three()
    {
        return $this->hasMany(FormDataThree::class);
    }

    public function form_type()
    {
      return $this->belongsTo(FormType::class);
    }

    public function report()
    {
      return $this->belongsTo(Report::class);
    }

}
