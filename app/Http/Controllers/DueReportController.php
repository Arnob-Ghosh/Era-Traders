<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Log;
class DueReportController extends Controller
{
    //
    public function Index(){

        return view('due-report.due-report');

    }
    public function due_data(Request $request)
    {


        $customers = Client::withCount('invoicePrices')
            ->withCount('deposits')
            ->withSum('invoicePrices as total_price', 'total_price')
            ->withSum('invoicePrices as invoice_paid', 'paid')
            ->withSum('deposits as deposit_paid', 'amount')
            ->get()
            ->map(function ($customer) {
                $customer->total_paid = $customer->invoice_paid + $customer->deposit_paid;
                $customer->total_due = $customer->total_price - $customer->total_paid;
                return $customer;
            });
            log::info($customers);

            return response()->json([
            'status' => 200,
            'data'=> $customers,
            'message' => 'Deposit recorded successfully!'
            ]);

    }

}
