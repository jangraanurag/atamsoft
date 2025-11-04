<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class unit extends Model
{
    use HasFactory;
    protected $table = 'units';
    protected $fillable = ['unit_name', 'unit_shorthand'];
    public $timestamps = true;
    public $primaryKey = 'id';
}
