<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class status extends Model
{
    use HasFactory;
    protected $table = 'statuses';
    protected $fillable = ['status_name'];
    public $timestamps = true;
    public $primaryKey = 'id';
}
