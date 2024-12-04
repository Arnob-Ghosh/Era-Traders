<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
class InventoryController extends Controller
{
    //
    public function report()
    {
        // Retrieve necessary data from models


        // Return view 'product.product-in' with data
        return view('inventory.invrentory-report');
    }
    public function report_data(Request $request)
    {
        $inventory = Inventory::get();
        $inventory_price = Inventory::select(DB::raw('SUM((unit_quantity * unit_price) + ((sub_unit_quantity - (unit_quantity * sub_unit)) * sub_unit_price)) as total_price'))->value('total_price');
        Log::info($inventory_price);
        // Return success JSON response
        return response()->json([
            'status' => 200,
            'data' => $inventory,
            'price' => $inventory_price,
        ]);

    }
}
