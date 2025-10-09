<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = ['created_at','updated_at'];

    public function form()
    {
      return $this->hasMany(Form::class);
    }

    public function form_type()
    {
      return $this->hasManyThrough(
        FormType::class,
        ShipTypeCategory::class,
        'category_id',
        'id',
        'id',
        'form_type_id',
      );
    }
    public function images()
    {
      return $this->hasMany(ReportImage::class);
    } 
}
