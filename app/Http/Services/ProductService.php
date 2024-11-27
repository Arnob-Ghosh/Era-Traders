<?php

namespace App\Http\Services;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Log;

class ProductService
{
   
    public function store(Request $request)
    {
        // Validation messages for required fields
        $messages = [
            'productName.required'    => "Product name is required.",
            'brand.required'          => "Brand name is required.",
            'category.required'       => "At least one category is required.",
        ];

        // Validation rules
        $validator = Validator::make($request->all(), [
            'productName'    => 'required',
            'brand'          => 'required',
            'category'       => 'required|array', // Ensure 'category' is an array
            'category.*'     => 'integer|exists:categories,id', // Validate each category ID
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

            foreach ($request->category as $categoryId) {
                // Create a new ProductCategory instance
                $productCategory = new ProductCategory();
    
                // Assign attributes from the request
                $productCategory->product_name = $request->productName;
                $productCategory->product_id = $product->id; // Set to null unless product linking logic exists
                $productCategory->category_id = $categoryId;
    
                // Optionally, fetch category name from the database
                $category = Category::find($categoryId);
                $productCategory->category_name = $category->category_name ?? 'Unknown';
    
                // Save the ProductCategory instance
                $productCategory->save();
            }


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
        $products = Product::with('categories')->get();

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
        Log::info($request->all());
        Log::info($id);
    
        // Validation messages for required fields
        $messages = [
            'productName.required' => "Product name is required.",
            'brand.required'       => "Brand name is required.",
            'edit_category.required' => "At least one category is required.",
        ];
    
        // Validation rules
        $validator = Validator::make($request->all(), [
            'productName'       => 'required',
            'brand'             => 'required',
            'edit_category'     => 'required|array', // Ensure 'edit_category' is an array
            'edit_category.*'   => 'integer|exists:categories,id', // Validate each category ID
        ], $messages);
    
        // Check if validation passes
        if ($validator->passes()) {
            // Find the product by ID
            $product = Product::find($id);
    
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }
    
            // Update product attributes with request data
            $product->productName = $request->productName;
            $product->brand = $request->brand;
            $product->save();
    
            // Synchronize categories
            $newCategoryIds = $request->edit_category;
    
            // Get current category IDs associated with the product
            $currentCategoryIds = $product->categories()->pluck('category_id')->toArray();
    
            // Find categories to add and remove
            $categoriesToAdd = array_diff($newCategoryIds, $currentCategoryIds);
            $categoriesToRemove = array_diff($currentCategoryIds, $newCategoryIds);
    
            // Remove old categories
            if (!empty($categoriesToRemove)) {
                ProductCategory::where('product_id', $id)
                    ->whereIn('category_id', $categoriesToRemove)
                    ->delete();
            }
    
            // Add new categories
            foreach ($categoriesToAdd as $categoryId) {
                $category = Category::find($categoryId);
    
                if ($category) {
                    ProductCategory::create([
                        'product_id'    => $id,
                        'category_id'   => $categoryId,
                        'product_name'  => $product->productName,
                        'category_name' => $category->category_name,
                    ]);
                }
            }
    
            // Return success JSON response
            return response()->json([
                'status' => 200,
                'message' => 'Product updated successfully!',
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

