<?php

namespace App\Http\Services;

use App\Models\Inventory;
use App\Models\ProductInHistory;
use Illuminate\Http\Request;
// use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Log;

class ProductInService
{

    public function store(Request $request)
    {
        try {
            // Validate input data
            $validator = Validator::make($request->all(), [
                'products' => 'required|array',
                'products.*.product' => 'required|integer',
                'products.*.category' => 'required|integer',
                'products.*.unit_id' => 'required|integer',
                'products.*.unit' => 'required|string',
                'products.*.sub_unit' => 'required|integer',
                'products.*.quantity' => 'required|numeric',
                'products.*.unit_price' => 'required|numeric',
                'products.*.product_name' => 'required|string',
                'products.*.category_name' => 'required|string',
            ]);
    
            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            // Begin database transaction
            DB::beginTransaction();
    
            foreach ($request->products as $productData) {
                // Ensure all inputs are cast to numeric types for calculations
                $unitPrice = (float) $productData['unit_price'];
                $quantity = (float) $productData['quantity'];
                $subUnitQuantity = (float) $productData['subunit'];
    
                // Perform numeric calculations
                $calculatedUnitPrice = $unitPrice / $quantity;
                $calculatedSubUnitPrice = $calculatedUnitPrice / $subUnitQuantity;
    
                ProductInHistory::create([
                    'product_id' => $productData['product'],
                    'product_name' => $productData['product_name'],
                    'category_id' => $productData['category'],
                    'category_name' => $productData['category_name'],
                    'quantity' => $quantity,
                    'unit_id' => $productData['unit_id'],
                    'unit' => $productData['unit'],
                    'sub_unit_id' => $productData['sub_unit'],
                    'sub_unit' => $productData['sub_unit_name'],
                    'sub_unit_quantity' => $subUnitQuantity,
                    'unit_price' => $calculatedUnitPrice,
                    'product_in_num' => 'PIN-', // Generate product in number (adjust format as needed)
                    'date' => now(),
                    'created_by' => Auth::user()->id,
                    'sub_unit_price' => $calculatedSubUnitPrice,
                ]);
    
                $inventory = Inventory::where([
                    ['product_id', $productData['product']],
                    ['unit_id', $productData['unit_id']],
                    ['sub_unit_id', $productData['sub_unit']],
                    ['unit_price', $calculatedUnitPrice],
                    ['sub_unit_price', $calculatedSubUnitPrice],
                    ['sub_unit', $subUnitQuantity],
                ])->first();
    
                if ($inventory) {
                    // Update the existing inventory record
                    $inventory->update([
                        'unit_quantity' => $inventory->unit_quantity + $quantity,
                        'sub_unit_quantity' => $inventory->sub_unit_quantity + $quantity * $subUnitQuantity,
                    ]);
                } else {
                    Inventory::create([
                        'product_name' => $productData['product_name'],
                        'product_id' => $productData['product'],
                        'category_id' => $productData['category'],
                        'category_name' => $productData['category_name'],
                        'unit_id' => $productData['unit_id'],
                        'unit_name' => $productData['unit'],
                        'sub_unit_id' => $productData['sub_unit'],
                        'sub_unit_name' => $productData['sub_unit_name'],
                        'sub_unit' => $subUnitQuantity,
                        'unit_quantity' => $quantity,
                        'sub_unit_quantity' => $quantity * $subUnitQuantity,
                        'unit_price' => $calculatedUnitPrice,
                        'sub_unit_price' => $calculatedSubUnitPrice,
                    ]);
                }
            }
    
            // Commit the transaction
            DB::commit();
    
            return response()->json(['success' => 'Products stored successfully']);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();
    
            // Log the error for debugging
            Log::error('Error storing products: ' . $e->getMessage());
    
            // Return a JSON response with the error
            return response()->json(['error' => 'An error occurred while storing products. Please try again later.'], 500);
        }
    }
    

    public function report_data(Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $product_in_history = ProductInHistory::whereBetween('date', [$startdate, $enddate])->get();

        // Return success JSON response
        return response()->json([
            'status' => 200,
            'data' => $product_in_history,
        ]);

    }

}
