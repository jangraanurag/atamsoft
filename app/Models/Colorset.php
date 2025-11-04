<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class colorset extends Model
{
    protected $table = 'colorsets';
    public $timestamps=true;
    public $primaryKey = 'id';
    protected $fillable = ['colorset_name'];
}
