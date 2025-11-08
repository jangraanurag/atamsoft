<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Brand;
use App\Models\Product;
use App\Models\unit;
use App\Models\d_unit;
use App\Models\Buyer;
use App\Models\supplier;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    // Caches for finding IDs
    private $brands;
    private $products;
    private $units;
    private $d_units;
    private $buyers;
    private $suppliers;

    /**
     * Show the import page.
     */
    public function index()
    {
        return view('import.index');
    }

    /**
     * Handle the CSV upload and import.
     */
    public function upload(Request $request)
    {
        // 1. Validate both files
        $request->validate([
            'orders_csv' => 'required|file|mimes:csv,txt|max:51200', // 50MB max
            'colors_csv' => 'required|file|mimes:csv,txt|max:51200', // 50MB max
        ]);

        try {
            // 2. Load all helper tables into memory for speed
            $this->loadCaches();
            set_time_limit(300); // 5 minutes max

            // ======================================================
            // STAGE 1: Process Orders CSV
            // ======================================================
            $this->info('Processing Orders CSV...');
            $ordersFile = $request->file('orders_csv');
            $ordersPath = $ordersFile->getRealPath();
            $handle = fopen($ordersPath, "r");
            
            // Set the delimiter to a semicolon (;)
            $header = array_map('trim', fgetcsv($handle, 0, ';'));
            
            $importedCount = 0;
            $skippedCount = 0;

            while (($row = fgetcsv($handle, 0, ';')) !== FALSE) {
                if (count($header) != count($row)) {
                    $skippedCount++;
                    continue; // Skip rows that don't match header count
                }
                // Handle potential encoding issues from Excel
                $data = array_combine($header, array_map('utf8_encode', $row));

                // Find or create placeholders for brands and products
                $brandId = $this->findOrCreateBrand($data['brand_name']);
                $productId = $this->findOrCreateProduct($data['product']);
                
                // Find or create a placeholder for the buyer
                $buyerId = $this->findOrCreateBuyer($data['bu_id']); 
                
                // Find the supplier, or use NULL
                $supplierId = $this->suppliers->has($data['su_id']) ? $data['su_id'] : null;
                
                // Extract complex strings (e.g., "1000 Pcs")
                $qtyData = $this->extractValueAndUnit($data['o_quantity']);
                $lenData = $this->extractValueAndUnit($data['length']);
                $widData = $this->extractValueAndUnit($data['width']);
                
                // Get the 'id' from the cached object
                $unitObj = $this->units->get($qtyData['unit']);
                $unitId = $unitObj ? $unitObj->id : null;
                
                $dunitObj = $this->d_units->get($lenData['unit']) ?? $this->d_units->get($widData['unit']);
                $dunitId = $dunitObj ? $dunitObj->id : null;

                $buyerAddress = $this->buyers->get($buyerId); // Get from our cache

                // Use updateOrCreate (like REPLACE INTO)
                Order::updateOrCreate(
                    ['id' => $data['id']],
                    [
                        // Foreign Keys
                        'order_brand' => $brandId,
                        'order_productName' => $productId,
                        
                        // *** THIS IS THE FIX: Check if label_type is numeric, otherwise set to NULL ***
                        'order_labelType' => is_numeric($data['label_type']) ? $data['label_type'] : null,
                        // *** END OF FIX ***

                        'order_buyer' => $buyerId,
                        'supplier_id' => $supplierId,
                        
                        'order_unit' => $unitId,
                        'order_d_unit' => $dunitId,
                        'order_qty' => $qtyData['value'],
                        'order_length' => $lenData['value'],
                        'order_width' => $widData['value'],
                        
                        'order_status' => $data['status'] == '' ? null : $data['status'],
                        'order_shipmentno' => $data['ship_no'] == '' ? null : $data['ship_no'],
                        'order_shipvia' => is_numeric($data['ship_via']) ? $data['ship_via'] : null,
                        
                        // Dates
                        'order_date' => $this->parseDate($data['order_date']),
                        'order_deliverydate' => $this->parseDate($data['order_delivery']),
                        
                        // Clean all numeric fields
                        'order_weight' => $this->cleanNumeric($data['weight']),
                        'order_manufacturedqty' => $this->cleanNumeric($data['m_quantity']),
                        'order_price' => $this->cleanNumeric($data['b_price']),
                        'order_mouldprice' => $this->cleanNumeric($data['b_m_price']),
                        'supplier_price' => $this->cleanNumeric($data['s_price']),
                        'supplier_mould_price' => $this->cleanNumeric($data['s_m_price']),
                        'buyer_total' => $this->cleanNumeric($data['b_amount']),
                        'supplier_total' => $this->cleanNumeric($data['s_amount']),

                        'order_salesman' => $data['salesman_id'],
                        'order_logo' => '', // As requested from your file
                        'order_image' => $data['image_i'],
                        'order_cdr' => $data['cdr'],
                        'order_po' => $data['image_po'],
                        
                        // Misc
                        'dieset' => (strtolower($data['dieset']) === 'yes'),
                        'colors' => null, // <-- Set to null for now
                        'order_remark' => $data['remark'],
                        'buyer_remark' => $data['b_remark'],
                        'supplier_remark' => $data['s_remark'],

                        // Addresses
                        'bill_to_name' => $buyerAddress->buyer_name ?? null,
                        'bill_to_address_line_1' => $buyerAddress->buyer_address ?? null,
                        'ship_to_name' => $buyerAddress->buyer_name ?? null,
                        'ship_to_address_line_1' => $buyerAddress->buyer_address ?? null,
                        
                        // Timestamps
                        'created_at' => $this->parseDate($data['order_date']),
                        'updated_at' => $this->parseDate($data['order_date']),
                    ]
                );
                $importedCount++;
            }
            fclose($handle);

            // ======================================================
            // STAGE 2: Process Colors CSV
            // ======================================================
            $this->info('Processing Colors CSV...');
            $colorsFile = $request->file('colors_csv');
            $colorsPath = $colorsFile->getRealPath();
            $handle = fopen($colorsPath, "r");

            // Set the delimiter to a semicolon (;)
            $header = array_map('trim', fgetcsv($handle, 0, ';'));
            $colorMap = [];

            // Read all colors into a map
            while (($row = fgetcsv($handle, 0, ';')) !== FALSE) {
                if (count($header) != count($row)) {
                    continue; // Skip bad rows
                }
                $data = array_combine($header, array_map('utf8_encode', $row));

                $oid = $data['oid'];
                if (!isset($colorMap[$oid])) {
                    $colorMap[$oid] = [];
                }
                
                // Add this color/qty object to the order's array
                $colorMap[$oid][] = [
                    'color' => $data['color'],
                    'qty'   => $data['qty'],
                ];
            }
            fclose($handle);

            // Now, update all the orders
            foreach ($colorMap as $oid => $colors) {
                $order = Order::find($oid);
                if ($order) {
                    $order->colors = $colors; // Automatically encodes to JSON
                    $order->save();
                }
            }
            
            $colorRowCount = count($colorMap);

        } catch (Exception $e) {
            Log::error('CSV Import Failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred during import: ' . $e->getMessage() . '. Check the log for details.']);
        }

        $successMessage = "Import successful!<br>
                           - Orders imported/updated: <strong>$importedCount</strong> (skipped $skippedCount)<br>
                           - Orders updated with colors: <strong>$colorRowCount</strong>";

        return back()->with('success', $successMessage);
    }
    
    /**
     * Helper function to safely split "1000 Pcs"
     */
    private function extractValueAndUnit($string)
    {
        if (empty($string)) {
            return ['value' => null, 'unit' => null];
        }

        // This regex splits "10.5 Pcs" into "10.5" and "Pcs"
        preg_match('/^([\d\.]+)\s*(.*)$/u', $string, $matches);

        if (count($matches) === 3) {
            return [
                'value' => floatval($matches[1]),
                'unit' => trim($matches[2])
            ];
        }
        
        if (is_numeric($string)) {
            return ['value' => floatval($string), 'unit' => null];
        }

        return ['value' => null, 'unit' => null];
    }

    /**
     * Helper function to parse dates, even if they are empty
     */
    private function parseDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }
        try {
            // Try parsing as Y-m-d first
            return \Carbon\Carbon::parse($dateString)->format('Y-m-d');
        } catch (Exception $e) {
            try {
                // Try parsing as d-m-Y as a fallback
                return \Carbon\Carbon::createFromFormat('d-m-Y', $dateString)->format('Y-m-d');
            } catch (Exception $e) {
                return null; // Return null if date is unparseable
            }
        }
    }
    
    /**
     * Helper function to clean "O.40" into "0.40"
     */
    private function cleanNumeric($string)
    {
        if ($string === '') {
            return null;
        }
        
        // Remove all characters that are NOT a digit (0-9) or a decimal point (.)
        $cleaned = preg_replace('/[^\d\.]/', '', $string);

        if ($cleaned === '') {
            return null;
        }

        return floatval($cleaned);
    }
    
    /**
     * Load all foreign key tables into memory for fast lookup.
     */
    private function loadCaches()
    {
        $this->info('Caching helper tables...');
        $this->brands = Brand::all()->keyBy('brand_name');
        $this->products = Product::all()->keyBy('product_name');
        $this->units = unit::all()->keyBy('unit_shorthand');
        $this->d_units = d_unit::all()->keyBy('dunit_shorthand');
        $this->buyers = Buyer::all()->keyBy('id');
        $this->suppliers = supplier::all()->keyBy('id'); 
    }

    /**
     * Find a Brand ID by name, or create it if it doesn't exist.
     */
    private function findOrCreateBrand($brandName)
    {
        if (empty($brandName)) {
            return null;
        }

        if (!$this->brands->has($brandName)) {
            $newBrand = [
                'brand_name' => $brandName,
                'created_at' => now(),
                'updated_at' => now()
            ];
            DB::table('brands')->insert($newBrand);
            $newBrand['id'] = DB::getPdo()->lastInsertId();
            $this->brands->put($brandName, (object)$newBrand);
            return $newBrand['id'];
        }

        return $this->brands->get($brandName)->id;
    }

    /**
     * Find a Product ID by name, or create it if it doesn't exist.
     */
    private function findOrCreateProduct($productName)
    {
        if (empty($productName)) {
            return null;
        }

        if (!$this->products->has($productName)) {
            $newProduct = [
                'product_name' => $productName,
                'created_at' => now(),
                'updated_at' => now()
            ];
            DB::table('products')->insert($newProduct);
            $newProduct['id'] = DB::getPdo()->lastInsertId();
            $this->products->put($productName, (object)$newProduct);
            return $newProduct['id'];
        }

        return $this->products->get($productName)->id;
    }

    /**
     * Find a Buyer ID, or create a placeholder *with the correct ID* if it doesn't exist.
     */
    private function findOrCreateBuyer($buyerId)
    {
        if (empty($buyerId) || !is_numeric($buyerId) || $buyerId == 0) {
            if (!$this->buyers->has(1)) {
                 DB::table('buyers')->insert([
                    'id' => 1,
                    'buyer_name' => 'Default Buyer (Orphan)',
                    'created_at' => now(),
                    'updated_at' => now()
                 ]);
                 $this->buyers->put(1, (object)['id' => 1, 'buyer_name' => 'Default Buyer (Orphan)']);
            }
            return 1; // Return the ID of the default buyer
        }

        // Check our cache first
        if ($this->buyers->has($buyerId)) {
            return $buyerId;
        }
        
        // It's an orphan ID. Create a placeholder to satisfy the NOT NULL constraint.
        $newBuyer = [
            'id' => $buyerId, // Use the old ID
            'buyer_name' => "Missing Buyer #{$buyerId}",
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        // This raw insert will use the 'id' provided.
        DB::table('buyers')->insert($newBuyer); 

        $this->buyers->put($buyerId, (object)$newBuyer); // Add to cache
        return $newBuyer['id']; // This is now a valid ID
    }

    // Helper to log info to the console (if running from command line)
    private function info($message)
    {
        if (app()->runningInConsole()) {
            $this->line($message);
        }
    }
}