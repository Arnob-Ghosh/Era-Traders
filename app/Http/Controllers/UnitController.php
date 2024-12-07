<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\UnitService;

class UnitController extends Controller
{
    protected $unit_service;

    public function __construct(UnitService $unit_service)
    {
        $this->unit_service = $unit_service;
    }

    // Display the unit creation form view.
    public function create()
    {
        return view('unit.unit-create');
    }

    // Store a newly created unit resource in storage.
    public function store(Request $request)
    {
        return $this->unit_service->store($request);
    }

    // Display a listing of the unit resources.
    public function list(Request $request)
    {
        return $this->unit_service->list($request);
    }

    // Show the form for editing the specified unit resource.
    public function edit($id)
    {
        return $this->unit_service->edit($id);
    }

    // Update the specified unit resource in storage.
    public function update(Request $request, $id)
    {
        return $this->unit_service->update($request, $id);
    }

    // Remove the specified unit resource from storage.
    public function destroy($id)
    {
        return $this->unit_service->destroy($id);
    }
}
