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
        // $inventory = Inventory::get();
        $inventory = Inventory::where('unit_quantity','>',0)->orWhere('sub_unit_quantity','>',0)->get();

        $inventory_price = Inventory::select(DB::raw('SUM((unit_quantity * unit_price) + ((sub_unit_quantity) * sub_unit_price)) as total_price'))->value('total_price');
        // Return success JSON response
        return response()->json([
            'status' => 200,
            'data' => $inventory,
            'price' => $inventory_price,
        ]);

    }
    public function quantiy_wise_report_data(Request $request)
    {

        $inventory = Inventory::select(
                'category_id',
                'sub_unit',
                'category_name',
                'unit_name',
                'sub_unit_name',
                'product_name',
                DB::raw('SUM(unit_quantity) as unit_quantity'),
                DB::raw('SUM(sub_unit_quantity) as sub_unit_quantity'),
                DB::raw('"-" as unit_price'),
                DB::raw('"-" as sub_unit_price')
            )   
            ->groupBy('category_id', 'sub_unit', 'category_name', 'unit_name', 'sub_unit_name','product_name')
            ->where('unit_quantity','>',0)->orWhere('sub_unit_quantity','>',0)
            ->get();

        $inventory_price = Inventory::select(DB::raw('SUM((unit_quantity * unit_price) + ((sub_unit_quantity) * sub_unit_price)) as total_price'))->value('total_price');
        return response()->json([
            'status' => 200,
            'data' => $inventory,
            'price' => $inventory_price,
        ]);

    }
}
