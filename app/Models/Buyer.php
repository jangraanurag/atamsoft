<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buyer extends Model
{
    use HasFactory;

    protected $table = 'buyers';
    public $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'buyer_name',
        'buyer_address',
        'buyer_city',
        'buyer_state',
        'buyer_country',
        'buyer_mobile_no',
        'buyer_email_id',
        'buyer_tax_no',
    ];

    /**
     * Get all of the brands for the Buyer.
     */
    public function brands()
    {
        return $this->hasMany(Brand::class, 'buyer_id');
    }

    /**
     * Get all of the orders for the Buyer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }
}
