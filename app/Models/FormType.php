<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormType extends Model
{
    use HasFactory;

    public function form()
    {
        return $this->hasMany(Form::class);
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, 'ship_type_categories', 'form_type_id', 'category_id');
    }

    public function shipTypeCategory()
    {
        return $this->hasMany(ShipTypeCategory::class);
    }

}
