<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use Log;

class SalesReturnController extends Controller
{
    //
    public function view()
    {
        // Retrieve necessary data from models

        $sales = Sales::get('ref_id');
        // Return view 'product.product-in' with data
        return view('sales_return.sales-return', compact('sales'));
    }



    public function sales_info_data(Request $request)
    {

        $sales = Sales::join('clients', 'sales.customer_id', '=', 'clients.id')
        ->select('sales.*', 'clients.mobile')
        ->where('sales.ref_id', $request->ref_id)
        ->get();

        return response()->json([
            'status' => 200,
            'data' => $sales,
        ]);
        
    }

    public function returnSale(Request $request)
    {
        DB::beginTransaction();
    
        try {
            $refId = $request->ref_id;
            $returnItems = $request->returns;
    
            $sales = Sales::where('ref_id', $refId)->get();
            $invoice = Invoiceprices::where('ref_id', $refId)->first();
    
            if (!$sales || $sales->isEmpty()) {
                throw new \Exception("No sales found for reference ID: $refId");
            }
    
            $returnTotal = 0;
    
            foreach ($returnItems as $item) {
                $sale = $sales->firstWhere('product_id', $item['product_id']);
                if (!$sale) {
                    throw new \Exception("Product ID {$item['product_id']} not found in this sale.");
                }
    
                $inventory = Inventory::find($item['product_id']);
                if (!$inventory) {
                    throw new \Exception("Inventory item not found for product ID: " . $item['product_id']);
                }
    
                // Update inventory
                if ($item['unit_name'] == $inventory->unit_name) {
                    $inventory->unit_quantity += $item['quantity'];
                } elseif ($item['unit_name'] == $inventory->sub_unit_name) {
                    $inventory->sub_unit_quantity += $item['quantity'];
                    if ($inventory->sub_unit_quantity >= $inventory->sub_unit) {
                        $inventory->unit_quantity += intdiv($inventory->sub_unit_quantity, $inventory->sub_unit);
                        $inventory->sub_unit_quantity %= $inventory->sub_unit;
                    }
                }
    
                $inventory->save();
    
                // Optionally log the return (if you have a Return model/table)
                ReturnLog::create([
                    'ref_id' => $refId,
                    'product_id' => $inventory->product_id,
                    'product_name' => $inventory->product_name,
                    'quantity' => $item['quantity'],
                    'unit_name' => $item['unit_name'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                    'date' => now(),
                    'customer_id' => $invoice->customer_id,
                    'customer_name' => $invoice->customer_name,
                ]);
    
                $returnTotal += $item['quantity'] * $item['price'];
            }
    
            // Optionally update the invoice if you want to subtract the return
            if ($invoice) {
                $invoice->total_price -= $returnTotal;
                $invoice->due -= $returnTotal;
                $invoice->save();
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Return processed successfully',
                'ref_id' => $refId,
                'total_refund' => $returnTotal,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Return error: ' . $e->getMessage());
    
            return response()->json([
                'message' => 'Failed to process return',
                'error' => $e->getMessage()
            ], 400);
        }
    }
    
}
