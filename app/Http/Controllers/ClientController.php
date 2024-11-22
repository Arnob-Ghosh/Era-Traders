<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ClientService;
use Log;

class ClientController extends Controller
{
    protected $client_service;

    // Constructor to inject the ClientService dependency
    public function __construct(ClientService $client_service)
    {
        $this->client_service = $client_service;
    }

    // Show the form to create a new client
    public function create()
    {
        // Return the view for creating a new client
        return view('client.client-create');
    }

    // Store a new client
    public function store(Request $request)
    {
        // Call the store method in ClientService to handle the request
        return $this->client_service->store($request);
    }

    // List all clients
    public function list(Request $request)
    {
        // Call the list method in ClientService to handle the request
        return $this->client_service->list($request);
    }

    // Show the form to edit an existing client
    public function edit($id)
    {
        // Call the edit method in ClientService to handle the request
        return $this->client_service->edit($id);
    }

    // Update an existing client
    public function update(Request $request, $id)
    {
        // Call the update method in ClientService to handle the request
        return $this->client_service->update($request, $id);
    }

    // Delete an existing client
    public function destroy($id)
    {
        // Call the destroy method in ClientService to handle the request
        return $this->client_service->destroy($id);
    }
}
