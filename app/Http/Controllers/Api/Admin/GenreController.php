<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Genre;

class GenreController extends Controller
{
    // Handle listing all genres
    public function index()
    {
        return response()->json([
            Genre::all()
        ]);
    }

    // Handle retrieving a specific genre by ID
    public function show($id)
    {
        try {
            // Try to find the genre by ID
            $genre = Genre::find($id);

            if (!$genre) {
                // If the genre is not found, return a 404 response
                return response()->json(['message' => 'Genre not found.'], 404);
            }

            // Return the genre as a JSON response
            return response()->json(['genre' => $genre]);
            
        } catch (ModelNotFoundException $e) {

            // If the genre is not found, return a 404 response
            return response()->json(['message' => 'Genre not found.'], 404);
        }
    }


    // Handle creating a new genre
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:25|unique:genres,name',
            ], [
                'name.required' => 'Please enter the genre name.',
                'name.unique' => 'This genre name already exists.',
                'name.max' => 'The genre name must not exceed 25 characters.',
            ]);

            // Create the genre
            Genre::create([
                'name' => $request->name,
            ]);

            return response()->json(['message' => 'Genre created successfully.'], 201);

        } catch (ValidationException $e) {
            
            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {

            // Handle other exceptions
            return response()->json([
                'message' => 'Failed to create genre. Please try again.',
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
                'name' => 'required|string|max:25|unique:genres,name,' . $id . ',genre_id',
            ], [
                'name.required' => 'Please enter the genre name.',
                'name.unique' => 'This genre name already exists.',
                'name.max' => 'The genre name must not exceed 25 characters.',
            ]);

            // Find the genre by ID
            $genre = Genre::find($id);

            // If the genre is not found, return a 404 response
            if (!$genre) {
                return response()->json(['message' => 'Genre not found.'], 404);
            }

            // Check if the name is the same as the current one
            if ($genre->name === $request->name) {
                return response()->json(['message' => 'No changes detected.'], 200);
            }

            // Update the genre
            $genre->name = $request->name;
            $genre->save();

            return response()->json(['message' => 'Genre updated successfully.']);

        } catch (ValidationException $e) {

            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {

            // Handle other exceptions
            return response()->json([
                'message' => 'Failed to update genre. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Handle deleting a genre
    public function destroy($id)
    {
        try {
            // Find the genre by ID
            $genre = Genre::find($id);

            // If the genre is not found, return a 404 response
            if (!$genre) {
                return response()->json(['message' => 'Genre not found.'], 404);
            }

            // Delete the genre
            $genre->delete();

            return response()->json(['message' => 'Genre deleted successfully.']);

        } catch (\Exception $e) {

            // Handle other exceptions
            return response()->json([
                'message' => 'Failed to delete genre. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
