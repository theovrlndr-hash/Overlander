<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingStatusMail;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    private function filtered(Request $request)
    {
        $query = Booking::with(['user', 'package', 'packagePlan', 'destinations'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q
                ->where('guest_name', 'like', "%{$s}%")
                ->orWhere('guest_email', 'like', "%{$s}%"));
        }

        return $query;
    }

    public function export(Request $request)
    {
        $bookings = $this->filtered($request)->get();

        // Spreadsheet apps run cells that start with = + - @ as formulas, and guests type these fields freely.
        $safe = fn ($v) => is_string($v) && $v !== '' && str_contains('=+-@', $v[0]) ? "'" . $v : $v;

        return response()->streamDownload(function () use ($bookings, $safe) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel reads accents correctly
            fputcsv($out, [
                'booking_id', 'created_at', 'status', 'guest_name', 'guest_email', 'guest_phone',
                'trip', 'plan', 'trip_date', 'travelers', 'total_price', 'payment_scheme', 'payment_status',
                'preferred_route', 'custom_request', 'message',
            ]);

            foreach ($bookings as $b) {
                fputcsv($out, array_map($safe, [
                    $b->id,
                    $b->created_at->toDateTimeString(),
                    $b->status,
                    $b->guest_name,
                    $b->guest_email,
                    $b->guest_phone,
                    $b->is_custom ? 'Custom trip' : $b->package?->name,
                    $b->packagePlan?->name,
                    $b->trip_date->toDateString(),
                    $b->pax,
                    $b->total_price,
                    $b->payment_scheme,
                    $b->payment_status,
                    $b->destinations->pluck('name')->join(' > '),
                    $b->custom_request,
                    $b->message,
                ]));
            }
            fclose($out);
        }, 'overlander-bookings-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function index(Request $request)
    {
        $query = $this->filtered($request);

        $bookings = $query->paginate(20)->withQueryString();
        $waLang = $request->query('wa') === 'en' ? 'en' : 'id';

        return view('admin.bookings.index', compact('bookings', 'waLang'));
    }

    public function calendar(Request $request)
    {
        $raw = (string) $request->query('month', '');
        $month = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $raw)
            ? Carbon::createFromFormat('Y-m-d', $raw . '-01')->startOfDay()
            : now()->startOfMonth();

        $showCancelled = $request->boolean('cancelled');
        $packageFilter = (string) $request->query('package', '');

        $query = Booking::with(['package', 'destinations'])
            ->whereBetween('trip_date', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->orderBy('trip_date')->orderBy('id');

        if (! $showCancelled) {
            $query->where('status', '!=', 'cancelled');
        }

        if ($packageFilter === 'custom') {
            $query->where('is_custom', true);
        } elseif (ctype_digit($packageFilter)) {
            $query->where('package_id', (int) $packageFilter);
        }

        $bookings = $query->get();
        $byDate = $bookings->groupBy(fn ($b) => $b->trip_date->toDateString());
        $packages = Package::orderBy('name')->get(['id', 'name', 'capacity']);
        $selectedPackage = ctype_digit($packageFilter) ? $packages->firstWhere('id', (int) $packageFilter) : null;

        return view('admin.bookings.calendar', compact(
            'month', 'bookings', 'byDate', 'packages', 'selectedPackage', 'packageFilter', 'showCancelled'
        ));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $previous = $booking->status;
        $booking->update(['status' => $request->status]);

        $message = "Booking #{$booking->id} status updated.";

        if ($previous !== $booking->status && $booking->status !== 'pending' && $booking->guest_email) {
            try {
                Mail::to($booking->guest_email)->send(new BookingStatusMail($booking));
                $message .= " Email sent to {$booking->guest_email}.";
            } catch (\Throwable $e) {
                Log::error('Booking status email failed: ' . $e->getMessage(), ['booking_id' => $booking->id]);
                $message .= ' The status was saved, but the email to the traveler could not be sent.';
            }
        }

        return back()->with('success', $message);
    }
}
