<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';
    public $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'brand_name',
        'brand_image',
        'buyer_id', // Added foreign key
    ];

    /**
     * Get the buyer that owns the brand.
     */
    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    // --- Existing Relationships ---

    public function orders()
    {
        return $this->hasMany(Order::class, 'order_brand', 'id');
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, Order::class, 'order_brand', 'id', 'id', 'order_productName');
    }
}
