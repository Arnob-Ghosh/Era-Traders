<?php

namespace App\Http\Services;

use DB; // Importing DB for database operations
use Log; // Importing Log for logging purposes

use App\Models\Client; // Importing Client model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientService
{

    // Store a newly created client.
    public function store(Request $request)
    {
        // Define custom error messages for validation
        $messages = [
            'customername.required' => "Name is required.",
            'mobile.required' => "Contact number is required.",
            'mobile.unique' => "Contact number already exists.",
        ];

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'customername' => 'required',
            'mobile' => 'required|unique:clients',
        ], $messages);

        // If validation passes, create and save the client
        if ($validator->passes()) {
            $customer = new Client;
            $customer->name = $request->customername;
            $customer->mobile = $request->mobile;
            $customer->email = $request->customeremail;
            $customer->address = $request->customeraddress;
            $customer->note = $request->note;
            $customer->save();

            // Return success response
            return response()->json([
                'status' => 200,
                'message' => 'Client created Successfully!',
            ]);
        }

        // If validation fails, return error messages
        return response()->json(['error' => $validator->errors()]);
    }

    // Retrieve list of clients.
    public function list(Request $request)
    {
        // Retrieve clients based on current user's subscriber_id
        $data = Client::get();

        // Return JSON response if request is AJAX
        return response()->json([
            'customer' => $data,
        ]);
    }

    // Retrieve a specific client for editing.
    public function edit($id)
    {
        $client = Client::find($id);

        // Return JSON response with client data
        if ($client) {
            return response()->json([
                'status' => 200,
                'client' => $client,
            ]);
        }
    }

    // Update the specified client.
    public function update(Request $request, $id)
    {
        // Define custom error messages for validation
        $messages = [
            'customername.required' => "Name is required.",
            'mobile.required' => "Contact number is required."
        ];

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'customername' => 'required',
            'mobile' => 'required'
        ], $messages);

        // If validation passes, update the client
        if ($validator->passes()) {
            $customer = Client::find($id);

            $customer->name = $request->customername;
            $customer->mobile = $request->mobile;
            $customer->email = $request->customeremail;
            $customer->address = $request->customeraddress;
            $customer->note = $request->note;

            $customer->save();
            // Return success response
            return response()->json([
                'status' => 200,
                'message' => 'Customer updated successfully'
            ]);
        }

        // If validation fails, return error messages
        return response()->json(['error' => $validator->errors()]);
    }

    // Remove the specified client from storage.
    public function destroy($id)
    {
        // Delete the client with the given id
        Client::find($id)->delete();

        // Redirect back with success message
        return redirect()->route('client.create')->with('status', 'Deleted successfully!');
    }

    // Fetch details of a specific client, including orders and payments.
    public function details(Request $request, $id)
    {
        $client = Client::find($id);

        // Fetch due payments for the client
        $duePayment = DB::table("due_payments")
            ->select(DB::raw("SUM(due_amount) as da"), "clientId")
            ->where([
                ['clientId', '=', $id],
                ['subscriber_id', '=', Auth::user()->subscriber_id]
            ])
            ->groupBy("clientId")
            ->get();

        // Count orders made by the client
        $ordersCount = Order::
            where([
                ['clientId', $id],
                ['subscriber_id', '=', Auth::user()->subscriber_id]
            ])
            ->count();

        // Fetch total payments made by the client
        $payments = DB::table("payments")
            ->select(DB::raw("SUM(total) as a"), "clientId")
            ->where([
                ['clientId', '=', $id],
                ['subscriber_id', '=', Auth::user()->subscriber_id]
            ])
            ->groupBy("clientId")
            ->get();

        // Return JSON response if request is AJAX
        if ($request->ajax()) {
            return response()->json([
                'message' => 'Success',
                'order' => $order, // This variable seems undefined; you might need to define it
            ]);
        }

        // Return view with client details, dues, orders count, and payments
        return view('customer/customer-details', [
            'customer' => $client,
            'dues' => $duePayment,
            'orders' => $ordersCount,
            'payments' => $payments
        ]);
    }
}

