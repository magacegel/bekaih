<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipTypeCategory extends Model
{
    use HasFactory;

    public function category()
    {
      return $this->belongsTo(Category::class);
    }
    public function form_type()
    {
      return $this->belongsTo(FormType::class);
    }
    public function ship_type()
    {
      return $this->belongsTo(ShipType::class);
    }
}
