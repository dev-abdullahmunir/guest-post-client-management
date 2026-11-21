<?php

namespace Modules\ClientManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Shared\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    /**
     * Render the Client Management Blade view.
     */
    public function renderView($viewFactory)
    {
        try {
            $clients = Client::latest()->get()->toArray();
        } catch (\Exception $e) {
            $clients = [];
        }

        return $viewFactory->make('clients.index', [
            'clients' => $clients
        ]);
    }

    /**
     * Display a listing of clients (API / JSON).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Client::query();

        if ($request && $request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
        }

        $clients = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $clients
        ]);
    }

    /**
     * Store a newly created client.
     */
    public function store(array $body): array
    {
        if (empty(trim($body['name'] ?? ''))) {
            http_response_code(422);
            return ['status' => 'error', 'message' => 'Client Name is required.'];
        }

        $client = Client::create([
            'name' => trim($body['name']),
            'email' => trim($body['email'] ?? null) ?: null,
            'phone' => trim($body['phone'] ?? null) ?: null,
            'company_name' => trim($body['company_name'] ?? null) ?: null,
            'country' => trim($body['country'] ?? null) ?: null,
            'notes' => trim($body['notes'] ?? null) ?: null,
        ]);

        return [
            'status' => 'success',
            'message' => 'Client added successfully!',
            'data' => $client
        ];
    }

    /**
     * Display the specified client.
     */
    public function show($id): array
    {
        $client = Client::findOrFail($id);
        return [
            'status' => 'success',
            'data' => $client
        ];
    }

    /**
     * Update the specified client.
     */
    public function update($id, array $body): array
    {
        if (empty(trim($body['name'] ?? ''))) {
            http_response_code(422);
            return ['status' => 'error', 'message' => 'Client Name is required.'];
        }

        $client = Client::findOrFail($id);
        $client->update([
            'name' => trim($body['name']),
            'email' => trim($body['email'] ?? null) ?: null,
            'phone' => trim($body['phone'] ?? null) ?: null,
            'company_name' => trim($body['company_name'] ?? null) ?: null,
            'country' => trim($body['country'] ?? null) ?: null,
            'notes' => trim($body['notes'] ?? null) ?: null,
        ]);

        return [
            'status' => 'success',
            'message' => 'Client updated successfully!',
            'data' => $client
        ];
    }

    /**
     * Remove the specified client (Soft delete).
     */
    public function destroy($id): array
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return [
            'status' => 'success',
            'message' => 'Client deleted successfully.'
        ];
    }
}
