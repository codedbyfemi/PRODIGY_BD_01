<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

class UserDetailsController extends Controller
{
    // Fetch all users from cache
    public function index(): JsonResponse
    {
        $users = Cache::get('users', []);
        return response()->json(array_values($users), 200);
    }

    // Store a new user in the cache
    public function store(Request $request): JsonResponse
    {
        // Validate request inputs
        $validated = $request->validate([
            'name'  => 'required|string',
            'email' => 'required|email',
            'age'   => 'required|integer|min:0',
        ]);

        // Retrieve current users and next available ID
        $users = Cache::get('users', []);
        $nextId = Cache::get('next_id', 1);

        // Create new user entry
        $user = array_merge(['id' => $nextId], $validated);
        $users[$nextId] = $user;

        // Update cache with new user and increment ID
        Cache::put('users', $users);
        Cache::put('next_id', $nextId + 1);

        return response()->json($user, 201);
    }

    // Get a specific user by ID
    public function show(int $id): JsonResponse
    {
        $users = Cache::get('users', []);
        $user = $users[$id] ?? null;

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    // Update an existing user
    public function update(Request $request, int $id): JsonResponse
    {
        $users = Cache::get('users', []);
        $user = $users[$id] ?? null;

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Validate only the fields provided
        $validated = $request->validate([
            'name'  => 'sometimes|required|string',
            'email' => 'sometimes|required|email',
            'age'   => 'sometimes|required|integer|min:0',
        ]);

        // Merge and save updated user
        $updatedUser = array_merge($user, $validated);
        $users[$id] = $updatedUser;

        Cache::put('users', $users);

        return response()->json($updatedUser, 200);
    }

    // Delete a user
    public function destroy(int $id): JsonResponse
    {
        $users = Cache::get('users', []);

        if (!isset($users[$id])) {
            return response()->json(['error' => 'User not found'], 404);
        }

        unset($users[$id]);
        Cache::put('users', $users);

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
