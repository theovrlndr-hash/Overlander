<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['destination', 'photos'])->latest()->paginate(12);

        return view('reviews.index', compact('reviews'));
    }

    public function store(Request $request, Destination $destination)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'photos' => 'nullable|array|max:3',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $review = $destination->reviews()->create([
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'user_id' => auth()->id(),
            'reviewer_name' => auth()->user()->name,
            'reviewer_avatar' => auth()->user()->avatar_url,
        ]);

        foreach ($request->file('photos', []) as $photo) {
            $review->photos()->create(['path' => $photo->store('uploads/reviews', 'public')]);
        }

        return back()->with('success', __('flash.review_thanks'));
    }
}
