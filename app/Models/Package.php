<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'category_id', 'name', 'slug', 'description_en', 'description_id',
        'cover_photo', 'is_featured', 'is_active', 'capacity', 'created_by',
        'duration_days', 'start_city', 'end_city',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected function description(): Attribute
    {
        return Attribute::get(function () {
            $locale = app()->getLocale();

            return $this->attributes["description_{$locale}"] ?? $this->attributes['description_en'] ?? null;
        });
    }

    protected function coverPhotoUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->cover_photo) {
                return null;
            }

            return str_starts_with($this->cover_photo, 'http') ? $this->cover_photo : asset('storage/' . $this->cover_photo);
        });
    }

    public function galleryPhotoUrls(): array
    {
        $photos = $this->destinations->pluck('cover_photo_url')->filter()->unique()->values();

        return $photos->isNotEmpty() ? $photos->all() : array_filter([$this->cover_photo_url]);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'package_destination')
            ->withPivot('order')
            ->orderBy('package_destination.order');
    }

    public function plans()
    {
        return $this->hasMany(PackagePlan::class);
    }

    public function itineraries()
    {
        return $this->hasMany(PackageItinerary::class)->orderBy('order');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Slots left per date for one month. Only dates that already have bookings appear;
     * every other date still has the full capacity. Empty when capacity is unlimited.
     *
     * @return array<string, int>
     */
    public function monthAvailability(\Illuminate\Support\Carbon $month): array
    {
        if ($this->capacity === null) {
            return [];
        }

        return $this->bookings()
            ->whereBetween('trip_date', [$month->copy()->startOfMonth()->toDateString(), $month->copy()->endOfMonth()->toDateString()])
            ->whereIn('status', ['pending', 'confirmed'])
            ->selectRaw('DATE(trip_date) as day, SUM(pax) as booked')
            ->groupBy('day')
            ->pluck('booked', 'day')
            ->map(fn ($booked) => max(0, $this->capacity - (int) $booked))
            ->all();
    }

    public function remainingCapacity(string $date, ?int $excludeBookingId = null): ?int
    {
        if ($this->capacity === null) {
            return null;
        }

        $booked = $this->bookings()
            ->whereDate('trip_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->when($excludeBookingId, fn ($q) => $q->where('id', '!=', $excludeBookingId))
            ->sum('pax');

        return max(0, $this->capacity - $booked);
    }

    /**
     * Rating of a package = average of the reviews of the destinations on its route.
     * One query for any number of packages: [package_id => ['avg' => float, 'count' => int]].
     */
    public static function ratingSummaries(iterable $packageIds): array
    {
        $ids = collect($packageIds)->values()->all();

        if (! $ids) {
            return [];
        }

        return \Illuminate\Support\Facades\DB::table('package_destination')
            ->join('reviews', 'reviews.destination_id', '=', 'package_destination.destination_id')
            ->whereIn('package_destination.package_id', $ids)
            ->groupBy('package_destination.package_id')
            ->selectRaw('package_destination.package_id as package_id, AVG(reviews.rating) as avg, COUNT(*) as count')
            ->get()
            ->mapWithKeys(fn ($r) => [$r->package_id => ['avg' => (float) $r->avg, 'count' => (int) $r->count]])
            ->all();
    }

    public function hasAccommodationOption(): bool
    {
        return $this->plans->flatMap->features->contains(fn ($f) => str_contains(strtolower($f), 'accommodation'));
    }

    public function hasMealsOption(): bool
    {
        return $this->plans->flatMap->features->contains(
            fn ($f) => str_contains(strtolower($f), 'meal') || str_contains(strtolower($f), 'snack')
        );
    }
}
