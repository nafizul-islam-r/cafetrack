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
            'image_url' => 'required|url|max:2048',
        ]);

        BoardGame::create([
            'name' => $validated['name'],
            'total_units' => $validated['total_units'],
            'available_units' => $validated['total_units'],
            'image_url' => $validated['image_url'],
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
            'image_url' => 'required|url|max:2048',
        ]);

        // Update the board game
        $boardGame->update($validated);

        return redirect()->route('board-games.index')->with('success', 'Board game updated successfully!');
    }


    public function destroy(BoardGame $boardGame)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        // Delete the database record
        $boardGame->delete();

        return redirect()->route('board-games.index')->with('success', 'Board game deleted successfully!');
    }
}
