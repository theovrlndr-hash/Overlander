<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with(['category', 'plans', 'destinations'])->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $durations = [
            '1' => [1, 1],
            '2-3' => [2, 3],
            '4-6' => [4, 6],
            '7+' => [7, 255],
        ];

        if (isset($durations[$request->query('duration')])) {
            $query->whereBetween('duration_days', $durations[$request->query('duration')]);
        }

        if (ctype_digit((string) $request->query('destination'))) {
            $query->whereHas('destinations', fn ($q) => $q->where('destinations.id', (int) $request->query('destination')));
        }

        match ($request->query('sort')) {
            'shortest' => $query->orderBy('duration_days'),
            'longest' => $query->orderByDesc('duration_days'),
            'newest' => $query->latest(),
            default => $query->orderByDesc('is_featured')->latest(),
        };

        $packages = $query->paginate(9)->withQueryString();
        $categories = Category::where('type', 'package')->orderBy('name')->get();
        $destinationOptions = Destination::whereHas('packages', fn ($q) => $q->where('is_active', true))->orderBy('name')->get(['id', 'name']);
        $durationOptions = array_keys($durations);

        $ratings = Package::ratingSummaries($packages->pluck('id'));

        return view('packages.index', compact('packages', 'categories', 'ratings', 'destinationOptions', 'durationOptions'));
    }

    public function show(Package $package)
    {
        $package->load(['category', 'plans', 'destinations', 'itineraries']);

        $rating = Package::ratingSummaries([$package->id])[$package->id] ?? null;

        return view('packages.show', compact('package', 'rating'));
    }

    public function availability(Request $request, Package $package)
    {
        $request->validate(['date' => 'required|date_format:Y-m-d']);

        return response()->json([
            'capacity' => $package->capacity,
            'remaining' => $package->remainingCapacity($request->date),
        ]);
    }

    public function availabilityMonth(Request $request, Package $package)
    {
        $request->validate(['month' => 'required|date_format:Y-m']);

        $month = \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $request->month . '-01')->startOfDay();

        return response()->json([
            'capacity' => $package->capacity,
            'days' => (object) $package->monthAvailability($month),
        ]);
    }
}
