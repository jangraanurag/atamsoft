<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// It's good practice to import all related models.
use App\Models\Brand;
use App\Models\Buyer;
use App\Models\d_unit;
use App\Models\LabelType;
use App\Models\Product;
use App\Models\shipper;
use App\Models\status;
use App\Models\supplier;
use App\Models\unit;


/**
 * Class Order
 *
 * Represents an order in the system.
 *
 * This model corresponds to the 'orders' table in the database and includes
 * various attributes related to the order, such as product details, buyer information,
 * shipment details, and more.
 */
class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    public $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * This array is updated to match all the fields from the latest migration,
     * allowing for mass assignment during creation and updates.
     *
     * @var array
     */
    protected $fillable = [
        // Core Details
        'id',
        'order_brand',
        'order_productName',
        'order_labelType',
        'order_buyer',
        'order_date',
        'order_deliverydate',

        // Quantity & Dimensions
        'order_weight',
        'order_qty',
        'order_unit',
        'order_manufacturedqty',
        'order_length',
        'order_width',
        'order_d_unit',

        // Materials & Colors
        'order_nailmaterial',
        'colors', // UPDATED: Replaces order_colorset

        // Shipping & Status
        'order_status',
        'order_shipmentno',
        'order_shipvia',
        'order_salesman',

        // Attachments
        'order_logo',
        'order_image',
        'order_cdr',
        'order_po',

        // Pricing
        'order_price',
        'order_mouldprice',
        'dieset',
        'buyer_total',

        // Supplier Info
        'supplier_id',
        'supplier_price',
        'supplier_mould_price',
        'supplier_total',

        // Remarks
        'order_remark',
        'buyer_remark',
        'supplier_remark',

        // Bill To Fields
        'bill_to_name',
        'bill_to_address_line_1',
        'bill_to_address_line_2',
        'bill_to_city',
        'bill_to_state',
        'bill_to_postal_code',
        'bill_to_country',

        // Ship To Fields
        'ship_to_name',
        'ship_to_address_line_1',
        'ship_to_address_line_2',
        'ship_to_city',
        'ship_to_state',
        'ship_to_postal_code',
        'ship_to_country',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * This improves data integrity and makes working with these fields easier.
     * The 'colors' attribute is cast to an array, automatically handling JSON encoding/decoding.
     *
     * @var array
     */
    protected $casts = [
        'dieset' => 'boolean',
        'colors' => 'array', // ADDED: Casts the JSON 'colors' column to a PHP array
        'order_weight' => 'float',
        'order_qty' => 'float',
        'order_manufacturedqty' => 'float',
        'order_length' => 'float',
        'order_width' => 'float',
        'order_date' => 'date',
        'order_deliverydate' => 'date',
        'order_price' => 'decimal:2',
        'order_mouldprice' => 'decimal:2',
        'buyer_total' => 'decimal:2',
        'supplier_price' => 'decimal:2',
        'supplier_mould_price' => 'decimal:2',
        'supplier_total' => 'decimal:2',
    ];


    // --- RELATIONSHIPS --- //

    public function product()
    {
        return $this->belongsTo(Product::class, 'order_productName');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'order_brand');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'order_buyer');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'order_unit');
    }

    public function labelType()
    {
        return $this->belongsTo(LabelType::class, 'order_labelType');
    }

    public function d_unit()
    {
        return $this->belongsTo(D_unit::class, 'order_d_unit');
    }

    public function shipper()
    {
        return $this->belongsTo(Shipper::class, 'order_shipvia');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'order_status');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
