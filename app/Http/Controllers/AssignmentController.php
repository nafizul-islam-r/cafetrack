<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\BoardGame;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AssignmentController extends Controller
{
    /**
     * Store a new assignment (check out a game).
     */
    public function store(Request $request)
    {
        // 1. Check if the logged-in user is an admin
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        // 2. Validate the form data
        $validated = $request->validate([
            'board_game_id' => 'required|exists:board_games,id',
            'student_id' => 'required|exists:users,student_id', // Check that this student ID exists in the users table
        ]);

        try {
            // Find the resources we need
            $user = User::where('student_id', $validated['student_id'])->firstOrFail();
            $boardGame = BoardGame::findOrFail($validated['board_game_id']);

            // 3. Check for business logic errors
            if ($boardGame->available_units <= 0) {
                return redirect()->back()->with('error', 'No available units to assign!');
            }

            // Check if this user already has this game assigned
            $existing = Assignment::where('user_id', $user->id)
                                  ->where('board_game_id', $boardGame->id)
                                  ->exists();

            if ($existing) {
                return redirect()->back()->with('error', 'This user already has this game assigned.');
            }

            // 4. Use a Database Transaction
            // This ensures that if one part fails, all parts fail.
            // It prevents us from assigning a game but forgetting to decrease the count.
            DB::transaction(function () use ($user, $boardGame) {
                // a) Create the new assignment record
                Assignment::create([
                    'user_id' => $user->id,
                    'board_game_id' => $boardGame->id,
                ]);

                // b) Decrement the available units on the board game
                $boardGame->decrement('available_units');
            });

            // 5. Redirect back with a success message
            return redirect()->back()->with('success', 'Game assigned successfully to ' . $user->name);

        } catch (\Exception $e) {
            // Catch any errors (e.g., user not found)
            return redirect()->back()->with('error', 'Failed to assign game: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified assignment (return a game).
     */
    public function destroy(Assignment $assignment)
    {
        // 1. Check if the logged-in user is an admin
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        try {
            // 2. Use a Database Transaction
            DB::transaction(function () use ($assignment) {
                // a) Increment the available units on the board game
                // We load the boardGame from the assignment relationship
                $assignment->boardGame()->increment('available_units');

                // b) Delete the assignment record
                $assignment->delete();
            });

            // 3. Redirect back with a success message
            return redirect()->back()->with('success', 'Game returned successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to return game: ' . $e->getMessage());
        }
    }
}