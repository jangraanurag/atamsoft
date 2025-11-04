<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class d_unit extends Model
{
    protected $table = 'd_units';
    protected $fillable = ['dunit_name', 'dunit_shorthand'];
    public $timestamps = true;
    public $primaryKey = 'id';
}
