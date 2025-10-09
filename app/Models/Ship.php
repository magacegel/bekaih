<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    use HasFactory;
    protected $guarded = ['created_at','updated_at'];

    public function report()
    {
      return $this->hasMany(Report::class);
    }


}
