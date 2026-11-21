<?php

namespace Modules\ClientManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ClientManagement\Models\Client;
use Modules\ClientManagement\Http\Requests\StoreClientRequest;
use Modules\ClientManagement\Http\Requests\UpdateClientRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Client::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
        }

        $clients = $query->latest()->paginate(15);

        return Response::json([
            'status' => 'success',
            'data' => $clients
        ]);
    }

    /**
     * Store a newly created client.
     */
    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = Client::create($request->validated());

        return Response::json([
            'status' => 'success',
            'message' => 'Client created successfully.',
            'data' => $client
        ], 201);
    }

    /**
     * Display the specified client.
     */
    public function show($id): JsonResponse
    {
        $client = Client::findOrFail($id);

        return Response::json([
            'status' => 'success',
            'data' => $client
        ]);
    }

    /**
     * Update the specified client.
     */
    public function update(UpdateClientRequest $request, $id): JsonResponse
    {
        $client = Client::findOrFail($id);
        $client->update($request->validated());

        return Response::json([
            'status' => 'success',
            'message' => 'Client updated successfully.',
            'data' => $client
        ]);
    }

    /**
     * Remove the specified client (Soft delete).
     */
    public function destroy($id): JsonResponse
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return Response::json([
            'status' => 'success',
            'message' => 'Client soft-deleted successfully.'
        ]);
    }
}
