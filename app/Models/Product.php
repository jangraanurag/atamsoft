<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_name',

    ];
    protected $table = 'products';
    public $timestamps = true; // Enable timestamps if needed
    public $softDeletes = true; // Enable soft deletes if needed
    public $primaryKey = 'id'; // Specify the primary key if it's not 'id'

}
