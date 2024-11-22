<?php

namespace App\Http\Controllers;

use Log; // Importing Log for logging purposes
use App\Models\Product; // Importing Product model
use Illuminate\Http\Request;
use App\Http\Services\ProductService;

class ProductController extends Controller
{
    // Property to hold the instance of ProductService
    protected $product_service;

    // Constructor to initialize the ProductService
    public function __construct(ProductService $product_service)
    {
        $this->product_service = $product_service;
    }

    // Function to display the create product form
    public function create()
    {
       

        // Return view 'product.product-create' with data
        return view('product.product-create');
    }

    // Function to handle storing a new product
    public function store(Request $request)
    {
        // Call the store method from ProductService and pass the request
        return $this->product_service->store($request);
    }

    // Function to display the product list view
    public function listView()
    {
        // Return view 'product/product-list'
        return view('product/product-list');
    }

    // Function to list products based on request parameters
    public function list(Request $request)
    {
        // Call the list method from ProductService and pass the request
        return $this->product_service->list($request);
    }

    // Function to display the edit product form
    public function edit(Request $request, $id)
    {
        

        // Find the product to be edited
        $edit_product = Product::find($id);
        // Find the subcategory of the product

        // Return view 'product.product_edit' with data
        return view('product.product_edit');
    }

    // Function to update an existing product
    public function update(Request $request, $id)
    {
        // Call the update method from ProductService and pass the request and product ID
        return $this->product_service->update($request, $id);
    }

    // Function to delete a product based on ID
    public function destroy($id)
    {
        // Call the destroy method from ProductService and pass the product ID
        return $this->product_service->destroy($id);
    }

}
?>
