<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductReturnHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductReturnController extends Controller
{
    public function view()
    {
        // Retrieve necessary data from models
        $products = Product::get();
        $categories = Category::get();

        // Return view 'product.product-in' with data
        return view('product.product-return', [
            'products' => $products,
            'categories' => $categories,
           
        ]);
    }
    
    public function store(Request $request)
{
    Log::info($request->all());
    try {
        // Validate input data
        $validator = Validator::make($request->all(), [
            
            'products' => 'required|array',
            'products.*.product_id' => 'required|integer',
            'products.*.category_id' => 'required|integer',
            'products.*.unit_quantity' => 'required|numeric|min:0',
            'products.*.unit_name' => 'required|string',
            'products.*.unit_price' => 'required|numeric|min:0', 
            'products.*.sub_unit' => 'required|numeric|min:0',
            'products.*.return_quantity' => 'required|numeric|min:1',
            'products.*.product_name' => 'required|string',
            'products.*.category_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        DB::beginTransaction();
        $return_number = 'PRN-' . now()->format('YmdHis');
        // Loop through each product in the request
        foreach ($request->products as $product) {
            $quantity = (float) $product['return_quantity'];

            // Check inventory for the product
            $inventory = Inventory::where([
                ['product_id', $product['product_id']],
                ['category_id', $product['category_id']], 
                ['unit_quantity', $product['unit_quantity']],
                ['unit_price', $product['unit_price']],
            ])->first();

            if (!$inventory) {
                return response()->json(['error' => 'Inventory not found for product: ' . $product['product_name'],'status'=>400]);
            }

            // Ensure sufficient inventory quantity
            if ($inventory->unit_quantity < $quantity) {
                return response()->json([
                    'error' => 'Insufficient quantity for product: ' . $product['product_name'],'status'=>400
                ]);
            }

            // Update inventory quantities
            $inventory->update([
                'unit_quantity' => $inventory->unit_quantity - $quantity,
            ]);

            // Create return history record
            ProductReturnHistory::create([
                'product_id' => $product['product_id'],
                'product_name' => $product['product_name'],
                'category_id' => $product['category_id'], 
                'category_name' => $product['category_name'],
                'unit_id' => $inventory->unit_id,
                'unit' => $product['unit_name'],
                'sub_unit_id' => $inventory->sub_unit_id,
                'sub_unit_name' => $inventory->sub_unit_name,
                'quantity' => $product['return_quantity'],
                'unit_price' => $product['unit_price'],
                'return_num' => $return_number,
                'date' => now(),
                'created_by' => Auth::user()->id,
                'note' => null,
            ]);
        }

        DB::commit();

        return response()->json(['success' => 'Product return processed successfully']);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error processing product return: ' . $e->getMessage() . ' Line: ' . $e->getLine()); 

        return response()->json(['error' => 'An error occurred while processing the return. Please try again later.','status'=>400]);
    }
}


// routes/web.php

// ProductController.php
public function getPrice($productId, $categoryId)
{
    $price = Inventory::where('product_id', $productId)
        ->where('category_id', $categoryId)
        ->get(); // Adjust the field name accordingly

    if ($price) {
        return response()->json(['status'=>200,'unit_price' => $price]);
    } else {
        return response()->json(['status'=>400,'message' => 'Product not found']);
    }
}

public function report_view ()
{

    return view('product.product-return-view');

}
public function report_data(Request $request)
{
    $data = ProductReturnHistory::get();
    // Return success JSON response
    return response()->json([
        'status' => 200,
        'data' => $data,
    ]);

}

}
