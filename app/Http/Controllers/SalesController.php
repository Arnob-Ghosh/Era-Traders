<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Inventory;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Log;

class SalesController extends Controller
{
    //
    public function view()
    {
        // Retrieve necessary data from models

        $customers = Client::all();
        // Return view 'product.product-in' with data
        return view('sales.sales', compact('customers'));
    }
    public function data(Request $request)
    {
        $inventory = Inventory::get();

        // Return success JSON response
        return response()->json([
            'status' => 200,
            'data' => $inventory,
        ]);
    }
    public function store(Request $request)
    {
        // Start a transaction
        DB::beginTransaction();
    
        try {
            Log::info($request);
    
            // Generate refId using current timestamp and a random string
            $refId = 'REF-' . now()->timestamp . '-' . strtoupper(Str::random(6));
    
            // Store the customer ID
            $customerId = $request->customer_id;
            $customer = Client::find($customerId);
    
            // Initialize variables for the invoice
            $invoiceItems = [];
            $subtotal = 0;
            $total = 0;
    
            // Loop through the sales items from the request
            foreach ($request->sales as $sale) {
                // Retrieve the inventory item by product_id
                $inventoryItem = Inventory::find($sale['product_id']);
    
                if ($inventoryItem) {
                    // Check if the inventory has enough quantity
                    if ($sale['unit_name'] == $inventoryItem->unit_name) {
                        if ($inventoryItem->unit_quantity < $sale['quantity']) {
                            // Throw exception if inventory quantity is less than the sale quantity
                            throw new \Exception("Not enough unit stock for product: " . $inventoryItem->product_name);
                        }
                    } elseif ($sale['unit_name'] == $inventoryItem->sub_unit_name) {
                        if ((($inventoryItem->unit_quantity * $inventoryItem->sub_unit)+$inventoryItem->sub_unit_quantity )< $sale['quantity']) {
                            // Throw exception if inventory sub-unit quantity is less than the sale quantity
                            throw new \Exception("Not enough sub-unit stock for product: " . $inventoryItem->product_name);
                        }
                    }
    
                    // Create a new Sales record
                    Sales::create([
                        'product_name' => $inventoryItem->product_name,
                        'product_id' => $inventoryItem->product_id,
                        'category_id' => $inventoryItem->category_id,
                        'category_name' => $inventoryItem->category_name,
                        'unit_id' => $inventoryItem->unit_id,
                        'unit_name' => $inventoryItem->unit_name,
                        'sub_unit_name' => $inventoryItem->sub_unit_name,
                        'sub_unit' => $inventoryItem->sub_unit,
                        'sub_unit_id' => $inventoryItem->sub_unit_id,
                        'unit_price' => $inventoryItem->unit_price,
                        'sub_unit_price' => $inventoryItem->sub_unit_price,
                        'sale_quantity' => $sale['quantity'],
                        'sale_unit' => $sale['unit_name'],
                        'sale_price' => $sale['price'],
                        'date' => now(),
                        'customer_name' => $customer->name,
                        'customer_id' => $customerId,
                        'ref_id' => $refId,
                    ]);
    
                    // Update the inventory quantity
                    if ($sale['unit_name'] == $inventoryItem->unit_name) {
                        $inventoryItem->unit_quantity -= $sale['quantity'];
                    } elseif ($sale['unit_name'] == $inventoryItem->sub_unit_name) {
                        if ($inventoryItem->sub_unit_quantity == 0) {
                            $inventoryItem->unit_quantity -= 1;
                            $inventoryItem->sub_unit_quantity = $inventoryItem->sub_unit - $sale['quantity'];
                        } else {
                            $inventoryItem->sub_unit_quantity -= $sale['quantity'];
                        }
                    }
    
                    $inventoryItem->save();
    
                    // Add this sale to the invoice items
                    $itemTotal = $sale['price'];
                    $invoiceItems[] = [
                        'product_name' => $inventoryItem->product_name,
                        'category' => $inventoryItem->category_name,
                        'price' => $sale['price'],
                        'sale_unit' => $sale['unit_name'],
                        'quantity' => $sale['quantity'],
                        'total' => $itemTotal,
                    ];
    
                    // Update the subtotal
                    $subtotal += $itemTotal;
                }
            }
    
            // Calculate the total
            $total = $subtotal ;
    
            // Commit the transaction
            DB::commit();
    
            // Return success response with invoice data
            return response()->json([
                'message' => 'Sales records have been stored successfully',
                'invoice_id' => $refId,  // Include the refId for invoice ID
                'customer_name' => $customer->name,
                'customer_mobile' => $customer->mobile,  // Assuming customer has an address
                'sales_items' => $invoiceItems,
                'subtotal' => $subtotal,
                'total' => $total,
            ]);
    
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
    
            // Log the error
            Log::error('Sales store error: ' . $e->getMessage());
    
            // Return failure response
            return response()->json([
                'message' => 'Failed to store sales records',
                'error' => $e->getMessage()
            ], 400);
        }
    }
    

}
