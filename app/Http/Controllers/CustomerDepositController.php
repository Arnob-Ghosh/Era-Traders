<?php

namespace App\Http\Controllers;

use App\Models\CustomerDeposit;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
class CustomerDepositController extends Controller
{
    public function create()
    {
        $refnumbers= Sales::select('id','ref_id')->get();
        return view('customer-deposit.deposit-create',compact('refnumbers'));
    }

    public function store(Request $req)
    {
        log::info($req->all());
        $messages = [
            'ref_id.unique' => "Reference ID already in use.",
            'amount.required' => "Amount is required.",
            'amount.numeric' => "Amount must be numeric.",
            'date.required' => "Date is required.",
        ];

        $validator = Validator::make($req->all(), [
            'ref_id' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ], $messages);

        if ($validator->passes()) {
            $deposit = new CustomerDeposit;
            $deposit->ref_id = $req->ref_id;
            $deposit->sales_id = $req->sales_id;
            $deposit->amount = $req->amount;
            $deposit->date = $req->date;
            $deposit->customer_id = Sales::where('id',$req->sales_id)->first()->customer_id;
            $deposit->save();

            return response()->json([
                'status' => 200,
                'message' => 'Deposit recorded successfully!'
            ]);
        }

        return response()->json(['error' => $validator->errors()]);
    }

    public function list(Request $request)
    {
        $deposits = CustomerDeposit::with('customer')->get();

        if ($request->ajax()) {
            return response()->json([
                'deposits' => $deposits,
            ]);
        }
    }

    public function edit($id)
    {
        $deposit = CustomerDeposit::find($id);

        if ($deposit) {
            return response()->json([
                'status' => 200,
                'deposit' => $deposit,
            ]);
        }

        return response()->json(['status' => 404, 'message' => 'Deposit not found']);
    }

    public function update(Request $req, $id)
    {
        $messages = [
            'amount.required' => "Amount is required.",
            'date.required' => "Date is required.",
        ];

        $validator = Validator::make($req->all(), [
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ], $messages);

        if ($validator->passes()) {
            $deposit = CustomerDeposit::find($id);
            $deposit->amount = $req->amount;
            $deposit->date = $req->date;
            $deposit->note = $req->note;
            $deposit->save();

            return response()->json([
                'status' => 200,
                'message' => 'Deposit updated successfully'
            ]);
        }

        return response()->json(['error' => $validator->errors()]);
    }

    public function destroy($id)
    {
        CustomerDeposit::find($id)?->delete();

        return redirect()->back()->with('status', 'Deposit deleted successfully!');
    }
}
