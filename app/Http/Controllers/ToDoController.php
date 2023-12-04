<?php

namespace App\Http\Controllers;

use App\Models\ToDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ToDoController extends Controller
{
    // List ToDos
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = ToDo::where('user_id', $user->id);

        // Search by name if 'search' query parameter is provided
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('title', 'like', '%'.$searchTerm.'%');
        }

        $todos = $query->paginate(10);

        return response()->json($todos);
    }

    // Create ToDo
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = Auth::user();

        $todo = new ToDo([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $user->id,
        ]);

        $todo->save();

        return response()->json(['message' => 'ToDo created successfully.', 'todo' => $todo], 201);
    }

    // View ToDo
    public function show(ToDo $todo)
    {
        $user = Auth::user();

        // Check if the authenticated user owns the requested ToDo
        if ($user->id !== $todo->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'title' => $todo->title,
            'description' => $todo->description,
        ]);
    }

    // Update ToDo
    public function update(Request $request, ToDo $todo)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = Auth::user();

        // Check if the authenticated user owns the requested ToDo
        if ($user->id !== $todo->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->save();

        return response()->json(['message' => 'ToDo updated successfully.', 'todo' => $todo]);
    }

    // Delete ToDo
    public function destroy(ToDo $todo)
    {
        $user = Auth::user();

        // Check if the authenticated user owns the requested ToDo
        if ($user->id !== $todo->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $todo->delete();

        return response()->json(['message' => 'ToDo deleted successfully.']);
    }
}
