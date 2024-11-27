<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductInService;
use App\Models\Category;
use App\Models\Product; // Importing Product model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importing Auth facade
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Log;
class ProductInController extends Controller
{
    // Property to hold the instance of ProductInService
    protected $productin_service;

    // Constructor to initialize the ProductInService
    public function __construct(ProductInService $productin_service)
    {
        $this->productin_service = $productin_service;
    }

    // Function to display the product in view
    public function view()
    {
        // Retrieve necessary data from models
        $products = Product::get();
        $categories = Category::get();

        // Return view 'product.product-in' with data
        return view('product.product-in', [
            'products' => $products,
            'categories' => $categories,
           
        ]);
    }

   
    // Function to store product in
    public function store(Request $request)
    {
        log::info($request);
        // Call the store method from ProductInService and pass the request
        return $this->productin_service->store($request);
    }
  
    
}

