<?php

namespace App\Http\Services;
use Log;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductService
{
   
    public function store(Request $request)
    {
        // Validation messages for required fields
        $messages = [
            'productName.required'    => "Product name is required.",
            'brand.required'          => "Brand name is required.",
        ];

        // Validation rules
        $validator = Validator::make($request->all(), [
            'productName'    => 'required',
            'brand'          => 'required',
        ], $messages);

        // Check if validation passes
        if ($validator->passes()) {
            // Create new Product instance
            $product = new Product;

            // Assign request data to Product model attributes
            $product->productName = $request->productName;
            $product->brand = $request->brand;
            $product->barcode = $request->productbarcode;

            $product->save();

            // Return success JSON response
            return response()->json([
                'status' => 200,
                'message' => 'Product added Successfully!'
            ]);
        }

        // Return validation errors if validation fails
        return response()->json(['error' => $validator->errors()]);
    }

    public function list(Request $request)
    {
        // Retrieve all products with category details
        $products = Product::get();

        // Check if request is AJAX
        if ($request->ajax()) {
            // Return products as JSON response
            return response()->json([
                'products' => $products,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        log::info($request);
        // Validation messages for required fields
        $messages = [
            'productName.required'    => "Product name is required.",
            'brand.required'      => "Brand name is required.",
        ];

        // Validation rules
        $validator = Validator::make($request->all(), [
            'productName'    => 'required',
            'brand'      => 'required',
        ], $messages);

        // Check if validation passes
        if ($validator->passes()) {
            // Find the product by ID
            $product = Product::find($id);

            // Update product attributes with request data
            $product->productName = $request->productName;
            $product->brand = $request->brand;
            // $product->barcode = $request->productbarcode;
            $product->save();

            // Return success JSON response
            return response()->json([
                'status' => 200,
                'message' => 'Product updated Successfully!'
            ]);
        }

        // Return validation errors if validation fails
        return response()->json(['error' => $validator->errors()]);
    }



    public function destroy($id)
    {
        // Find and delete the product by ID
        Product::find($id)->delete();

        // Redirect with success message
        return redirect('product-create')->with('status', 'Deleted successfully!');
    }
}

