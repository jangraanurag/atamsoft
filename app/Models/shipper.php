<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class shipper extends Model
{
    use HasFactory;
    protected $table = 'shippers';
    protected $fillable = [
        'shipper_name',
    ];
    public $timestamps = true; // Enable timestamps if needed
    public function orders()
    {
        return $this->hasMany('Order', 'order_shipvia', 'id');
    }


}
