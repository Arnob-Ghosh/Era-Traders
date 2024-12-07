<?php

namespace App\Http\Services;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UnitService
{
    /**
     * Store a newly created unit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation messages for form fields
        $messages = [
            'unitname.required'  =>    "Unit name is required.",
        ];

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'unitname' => 'required',
        ], $messages);

        // If validation passes, create a new unit
        if ($validator->passes()) {
            $unit = new Unit;

            // Assign values from request to unit model
            $unit->name = $request->unitname;
            $unit->description = $request->description;

            // Save the unit
            $unit->save();

            // Return success JSON response
            return response()->json([
                'status' => 200,
                'message' => 'Unit created successfully!'
            ]);
        }

        // Return JSON response with validation errors if validation fails
        return response()->json(['error' => $validator->errors()]);
    }

    /**
     * Display a listing of the units.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $units = Unit::get();

        // Return JSON response with units data for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'unit' => $units,
                'message' => 'Success'
            ]);
        }
    }

    /**
     * Show the form for editing the specified unit.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        // Find unit by id
        $unit = Unit::find($id);

        // If unit found, return JSON response with unit data
        if ($unit) {
            return response()->json([
                'status' => 200,
                'unit' => $unit,
            ]);
        }
    }

    /**
     * Update the specified unit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validation messages for form fields
        $messages = [
            'unitname.required'  =>    "Unit name is required.",
        ];

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'unitname' => 'required',
        ], $messages);

        // If validation passes, update the unit
        if ($validator->passes()) {
            $unit = Unit::find($id);

            // Assign updated values from request to unit model
            $unit->name = $request->unitname;
            $unit->description = $request->description;

            // Save the updated unit
            $unit->save();

            // Return success JSON response
            return response()->json([
                'status' => 200,
                'message' => 'Unit updated successfully'
            ]);
        }

        // Return JSON response with validation errors if validation fails
        return response()->json(['error' => $validator->errors()]);
    }

    /**
     * Remove the specified unit from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Find unit by id and delete it
        Unit::find($id)->delete();

        // Redirect to unit creation page with success message
        return redirect()->route('unit.create')->with('status', 'Deleted successfully!');
    }
}

