<?php

namespace App\Support;

use App\Models\Booking;

/**
 * Ready-made WhatsApp replies the admin can send to a traveler from the bookings list / calendar.
 * Each template exists in Indonesian and English; the admin picks the language per page.
 */
class BookingMessages
{
    public const LANGS = ['id' => 'ID', 'en' => 'EN'];

    private const LABELS = [
        'confirm' => 'Confirm booking',
        'deposit' => 'Ask for deposit',
        'date_unavailable' => 'Date is full',
        'reminder' => 'Trip reminder',
        'thanks' => 'Thank you + review',
        'custom_quote' => 'Custom trip: ask details',
    ];

    /** Templates that make sense for this booking, as [key => label]. */
    public static function available(Booking $booking): array
    {
        $keys = $booking->is_custom
            ? ['custom_quote', 'confirm', 'deposit', 'reminder', 'thanks']
            : ['confirm', 'deposit', 'date_unavailable', 'reminder', 'thanks'];

        return collect($keys)->mapWithKeys(fn ($k) => [$k => self::LABELS[$k]])->all();
    }

    /** wa.me link with the message prefilled, or null when the booking has no usable phone number. */
    public static function url(Booking $booking, string $key, string $lang = 'id'): ?string
    {
        $phone = self::phone($booking->guest_phone);

        if (! $phone || ! isset(self::LABELS[$key])) {
            return null;
        }

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode(self::text($booking, $key, $lang));
    }

    public static function phone(?string $raw): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $raw);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        // Local numbers typed without the leading zero (e.g. 8564...).
        if (str_starts_with($digits, '8')) {
            return '62' . $digits;
        }

        return $digits;
    }

    public static function text(Booking $booking, string $key, string $lang = 'id'): string
    {
        $lang = $lang === 'en' ? 'en' : 'id';
        $en = $lang === 'en';

        $name = $booking->guest_name;
        $id = $booking->id;
        $date = $booking->trip_date->format('d M Y');
        $pax = $booking->pax;
        $trip = $booking->is_custom
            ? 'custom trip'
            : ($booking->package?->name ?? ($en ? 'your trip' : 'tripmu'));
        $sign = "\n\n— The Overlander";

        $route = $booking->is_custom ? $booking->destinations->pluck('name')->join(' → ') : '';

        return match ($key) {
            'confirm' => $en
                ? "Hi {$name}, your booking #{$id} ({$trip}) on {$date} for {$pax} traveler(s) is confirmed. See you on the trip!{$sign}"
                : "Halo {$name}, pesanan #{$id} ({$trip}) tanggal {$date} untuk {$pax} orang sudah kami konfirmasi. Sampai ketemu di trip!{$sign}",

            'deposit' => $en
                ? "Hi {$name}, to secure your slot for booking #{$id} ({$trip}, {$date}) we need a deposit. We'll send the payment details here — please reply to confirm.{$sign}"
                : "Halo {$name}, untuk mengamankan slot pesanan #{$id} ({$trip}, {$date}) kami butuh DP. Detail pembayaran kami kirim di sini — mohon balas pesan ini kalau sudah siap.{$sign}",

            'date_unavailable' => $en
                ? "Hi {$name}, sorry — {$date} is already full for {$trip} (booking #{$id}). Could you move to another date? We'll help you find one that works.{$sign}"
                : "Halo {$name}, mohon maaf, tanggal {$date} untuk {$trip} (pesanan #{$id}) sudah penuh. Apakah bisa pindah ke tanggal lain? Kami bantu carikan yang cocok.{$sign}",

            'reminder' => $en
                ? "Hi {$name}, a quick reminder: your {$trip} trip (booking #{$id}) is on {$date} for {$pax} traveler(s). Let us know if anything changes!{$sign}"
                : "Halo {$name}, pengingat: trip {$trip} kamu (pesanan #{$id}) tanggal {$date} untuk {$pax} orang. Kabari kami kalau ada perubahan ya!{$sign}",

            'thanks' => $en
                ? "Hi {$name}, thank you for travelling with us! If you have a minute, we'd love a review on our website: " . url('/reviews') . $sign
                : "Halo {$name}, terima kasih sudah ikut trip bareng kami! Kalau sempat, boleh tulis ulasan di website kami: " . url('/reviews') . $sign,

            'custom_quote' => $en
                ? "Hi {$name}, thanks for your custom trip request #{$id}" . ($route ? " ({$route})" : '') . ". Could you tell us how many days, your rough budget and the accommodation you prefer? Then we can prepare an offer.{$sign}"
                : "Halo {$name}, terima kasih sudah mengirim permintaan custom trip #{$id}" . ($route ? " ({$route})" : '') . ". Boleh kami tahu berapa hari, kisaran budget, dan akomodasi yang kamu mau? Nanti kami buatkan penawarannya.{$sign}",

            default => '',
        };
    }
}
