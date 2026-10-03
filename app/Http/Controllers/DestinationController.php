<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = Destination::with('categories')->withAvg('reviews', 'rating')->withCount('reviews')->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $request->category));
        }

        $destinations = $query->orderBy('name')->paginate(9)->withQueryString();
        $categories = Category::where('type', 'destination')->orderBy('name')->get();

        return view('destinations.index', compact('destinations', 'categories'));
    }

    public function show(Destination $destination)
    {
        $destination->load(['categories', 'photos', 'activities', 'packages']);

        $reviews = $destination->reviews()->with('photos')->latest()->paginate(10);
        $ratingAvg = $destination->reviews()->avg('rating');
        $ratingCount = $destination->reviews()->count();

        return view('destinations.show', compact('destination', 'reviews', 'ratingAvg', 'ratingCount'));
    }
}
