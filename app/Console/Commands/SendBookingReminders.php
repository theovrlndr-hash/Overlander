<?php

namespace App\Console\Commands;

use App\Mail\BookingReminderMail;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders {--dry-run : List who would get a reminder without sending anything}';

    protected $description = 'Email confirmed travelers a reminder about 7 days and 1 day before their trip';

    public function handle(): int
    {
        $today = now()->startOfDay();
        $sent = 0;
        $failed = 0;

        // The windows are ranges, not exact days, so a missed run (deploy, outage) still catches up.
        // Each reminder is sent once: it is stamped only after the email went out.
        $jobs = [
            ['week', 'reminder_week_sent_at', $today->copy()->addDays(2), $today->copy()->addDays(7)],
            ['day', 'reminder_day_sent_at', $today->copy()->addDay(), $today->copy()->addDay()],
        ];

        foreach ($jobs as [$kind, $column, $from, $to]) {
            $bookings = Booking::with('package')
                ->where('status', 'confirmed')
                ->whereNull($column)
                ->whereNotNull('guest_email')
                ->whereBetween('trip_date', [$from->toDateString(), $to->toDateString()])
                ->get();

            foreach ($bookings as $booking) {
                $line = "{$kind}: #{$booking->id} {$booking->guest_name} <{$booking->guest_email}> trip {$booking->trip_date->toDateString()}";

                if ($this->option('dry-run')) {
                    $this->line('[dry-run] ' . $line);
                    continue;
                }

                try {
                    Mail::to($booking->guest_email)->send(new BookingReminderMail($booking, $kind));
                    $booking->forceFill([$column => now()])->save();
                    $sent++;
                    $this->line('sent ' . $line);
                } catch (\Throwable $e) {
                    $failed++;
                    Log::error('Booking reminder failed: ' . $e->getMessage(), ['booking_id' => $booking->id, 'kind' => $kind]);
                    $this->error('failed ' . $line . ' — ' . $e->getMessage());
                }
            }
        }

        $this->info("Reminders sent: {$sent}, failed: {$failed}");

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
