<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Distributor;

class DistributorController extends Controller
{
    // Handle listing all genres
    public function index()
    {
        $distributors = Distributor::all();

        if ($distributors->isEmpty()) {
            return response()->json(['message' => 'No distributors found.'], 404);
        }

        return response()->json(['distributors' => $distributors]);
    }

    // Handle retrieving a specific genre by ID
    public function show($id)
    {
        try {
            // Try to find the genre by ID
            $distributor = Distributor::find($id);

            if (!$distributor) {
                // If the distributor is not found, return a 404 response
                return response()->json(['message' => 'Distributor not found.'], 404);
            }

            // Return the distributor as a JSON response
            return response()->json(['distributor' => $distributor]);
            
        } catch (ModelNotFoundException $e) {

            // If the distributor is not found, return a 404 response
            return response()->json(['message' => 'Distributor not found.'], 404);
        }
    }


    // Handle creating a new distributor
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:50|unique:distributors,name',
            ], [
                'name.required' => 'Please enter the distributor name.',
                'name.unique' => 'This distributor name already exists.',
                'name.max' => 'The distributor name must not exceed 50 characters.',
            ]);

            // Create the distributor
            Distributor::create([
                'name' => $request->name,
            ]);

            return response()->json(['message' => 'Distributor created successfully.'], 201);

        } catch (ValidationException $e) {
            
            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {

            // Handle other exceptions
            return response()->json([
                'message' => 'Failed to create distributor. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Handle updating an existing genre
    public function update(Request $request, $id)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:25|unique:distributors,name,' . $id . ',distributor_id',
            ], [
                'name.required' => 'Please enter the distributor name.',
                'name.unique' => 'This distributor name already exists.',
                'name.max' => 'The distributor name must not exceed 25 characters.',
            ]);

            // Find the distributor by ID
            $distributor = Distributor::find($id);

            // If the distributor is not found, return a 404 response
            if (!$distributor) {
                return response()->json(['message' => 'Distributor not found.'], 404);
            }

            // Check if the name is the same as the current one
            if ($distributor->name === $request->name) {
                return response()->json(['message' => 'No changes detected.'], 200);
            }

            // Update the distributor
            $distributor->name = $request->name;
            $distributor->save();

            return response()->json(['message' => 'Distributor updated successfully.']);

        } catch (ValidationException $e) {

            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {

            // Handle other exceptions
            return response()->json([
                'message' => 'Failed to update distributor. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Handle deleting a distributor
    public function destroy($id)
    {
        try {
            // Find the distributor by ID
            $distributor = Distributor::find($id);

            // If the distributor is not found, return a 404 response
            if (!$distributor) {
                return response()->json(['message' => 'Distributor not found.'], 404);
            }

            // Delete the distributor
            $distributor->delete();

            return response()->json(['message' => 'Distributor deleted successfully.']);

        } catch (\Exception $e) {

            // Handle other exceptions
            return response()->json([
                'message' => 'Failed to delete distributor. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
