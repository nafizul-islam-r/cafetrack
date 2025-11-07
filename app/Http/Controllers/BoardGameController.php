<?php

namespace App\Http\Controllers;

use App\Models\BoardGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BoardGameController extends Controller
{
    public function publicIndex()
    {
        $boardGames = BoardGame::all();
        return view('board-games.public-index', [
            'boardGames' => $boardGames
        ]);
    }

    public function index()
    {
        $boardGames = BoardGame::all();
        return view('board-games.index', [
            'boardGames' => $boardGames
        ]);
    }

    public function create()
    {
        // Only allow admins to see this page
        if (!Gate::allows('is-admin')) {
            abort(403);
        }
        return view('board-games.create');
    }

    public function store(Request $request)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_units' => 'required|integer|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('image')->store('board-games', 'public');

        BoardGame::create([
            'name' => $validated['name'],
            'total_units' => $validated['total_units'],
            'available_units' => $validated['total_units'],
            'image_url' => $imagePath,
        ]);

        return redirect()->route('board-games.index')->with('success', 'Board game added successfully!');
    }

    public function show(BoardGame $boardGame)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        // Eager-load the assignments and their related user data
        $assignments = $boardGame->assignments()->with('user')->orderBy('created_at', 'desc')->get();

        return view('board-games.show', [
            'boardGame' => $boardGame,
            'assignments' => $assignments
        ]);
    }

    public function edit(BoardGame $boardGame)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        return view('board-games.edit', [
            'boardGame' => $boardGame
        ]);
    }

    public function update(Request $request, BoardGame $boardGame)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_units' => 'required|integer|min:0',
            'available_units' => 'required|integer|min:0|lte:total_units',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Check if a new image was uploaded
        if ($request->hasFile('image')) {
            // Delete the old image
            Storage::disk('public')->delete($boardGame->image_url);

            // Store the new image and update the path
            $validated['image_url'] = $request->file('image')->store('board-games', 'public');
        }

        // Update the board game
        $boardGame->update($validated);

        return redirect()->route('board-games.index')->with('success', 'Board game updated successfully!');
    }


    public function destroy(BoardGame $boardGame)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        // Delete the associated image file
        Storage::disk('public')->delete($boardGame->image_url);

        // Delete the database record
        $boardGame->delete();

        return redirect()->route('board-games.index')->with('success', 'Board game deleted successfully!');
    }
}
