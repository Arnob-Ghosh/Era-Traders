<?php

namespace App\Http\Services;

use App\Models\ProductInHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Models\Inventory;
use Illuminate\Support\Facades\Validator;
use Log;

class ProductInService
{

    public function store(Request $request)
    {
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

        // Loop through products and store them in ProductInHistory
        foreach ($request->products as $productData) {
            ProductInHistory::create([
                'product_id' => $productData['product'],
                'product_name' => $productData['product_name'],
                'category_id' => $productData['category'],
                'category_name' => $productData['category_name'],
                'quantity' => $productData['quantity'],
                'unit_id' => $productData['unit_id'],
                'unit' => $productData['unit'],
                'sub_unit_id' => $productData['sub_unit'],
                'sub_unit' => $productData['sub_unit_name'],
                'unit_price' => $productData['unit_price'],
                'sub_unit_price' => $productData['unit_price'], // Assuming it's the same, adjust if needed
                'created_by' => Auth::user()->id,
                'product_in_num' => 'PIN-', // Generate product in number (adjust format as needed)
                'date' => now(),
            ]);
        }

        // Log the successful data
        Log::info('Product data stored successfully', $request->products);

        return response()->json(['success' => 'Products stored successfully']);
    }

}
