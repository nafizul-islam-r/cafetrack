<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the form data
        $validated = $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id(); // Get the currently logged-in user's ID

        // 2. We no longer check for an existing review.
        //    We just create a new one every time.
        Review::create([
            'user_id' => $userId,
            'food_item_id' => $validated['food_item_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // 3. Redirect back with a success message
        return redirect()->back()->with('success', 'Thank you for your review!');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        // 1. Authorize the deletion
        if (!Gate::allows('is-admin') && $review->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Delete the review
        $review->delete();

        // 3. Redirect back with a success message
        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
