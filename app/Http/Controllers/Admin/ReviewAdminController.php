<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['destination', 'user', 'photos'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('reviewer_name', 'like', "%{$s}%");
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->paginate(20)->withQueryString();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleFeatured(Review $review)
    {
        $review->update(['is_featured' => ! $review->is_featured]);
        return back()->with('success', 'Review featured status updated.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted successfully.');
    }
}
