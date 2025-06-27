<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Role;


class AuthenticationController extends Controller
{
    // Handle user registration
    public function register(Request $request)
    {
        
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'role_id' => 'nullable|exists:roles,role_id',
            ], [
                'name.required' => 'Please enter your full name.',
                'email.required' => 'We need your email address.',
                'email.email' => 'Your email address is not valid.',
                'email.unique' => 'This email is already registered.',
                'password.required' => 'Please provide a password.',
                'password.confirmed' => 'The password confirmation does not match.',
                'role_id.exists' => 'The selected role is invalid.',
            ]);

            // Check if role_id is provided, otherwise use default role
            if ($request->filled('role_id')) {
                $roleId = $request->role_id;
            } else {
                $defaultRole = Role::where('name', 'user')->first();
                if (!$defaultRole) {
                    return response()->json(['error' => 'Default role not found.'], 500);
                }
                $roleId = $defaultRole->role_id;
            }

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $roleId,
            ]);

            // Create a personal access token for the user
            $token = $user->createToken('Personal Access Token')->accessToken;

            return response()->json(['token' => $token], 201);
        } catch (ValidationException $e) {

            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {

            // Handle other exceptions
            return response()->json([
                'message' => 'Registration failed. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Handle user login
    public function login(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ], [
                'email.required' => 'We need your email address.',
                'email.email' => 'Your email address is not valid.',
                'password.required' => 'Password is required.',
            ]);

            // Attempt to authenticate the user
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Wrong email or password.'], 401);
            }

            // If authentication is successful, create a personal access token
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->accessToken;

            return response()->json(['token' => $token]);
        } catch (\Illuminate\Validation\ValidationException $e) {

            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {

            // Handle other exceptions
            return response()->json([
                'message' => 'Login failed. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}