<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
@php
    $trip = $booking->is_custom ? 'Custom trip' : ($booking->package?->name ?? '-');
    $date = $booking->trip_date->format('d M Y');
    $days = max(0, (int) now()->startOfDay()->diffInDays($booking->trip_date, false));
    $copy = $kind === 'day'
        ? [
            'en' => "A quick reminder: your trip {$trip} (booking #{$booking->id}) is tomorrow, {$date}, for {$booking->pax} traveler(s). Please keep your phone close, our team will message you on WhatsApp about the meeting point.",
            'id' => "Pengingat singkat: trip {$trip} kamu (pesanan #{$booking->id}) besok, {$date}, untuk {$booking->pax} orang. Tolong pantau ponselmu, tim kami akan menghubungi lewat WhatsApp soal titik kumpul.",
        ]
        : [
            'en' => "Your trip {$trip} (booking #{$booking->id}) is in {$days} days, on {$date}, for {$booking->pax} traveler(s). If anything changes, such as the number of travelers or your phone number, please let us know on WhatsApp.",
            'id' => "Trip {$trip} kamu (pesanan #{$booking->id}) tinggal {$days} hari lagi, yaitu {$date}, untuk {$booking->pax} orang. Kalau ada perubahan, misalnya jumlah orang atau nomor telepon, kabari kami lewat WhatsApp ya.",
        ];
@endphp
<body style="font-family: Arial, sans-serif; color: #1f2937; max-width: 480px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 20px; color: #ea580c;">Overlander</h1>

    <p>Hi {{ $booking->guest_name }},</p>
    <p>{{ $copy['en'] }}</p>

    <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">

    <p>Halo {{ $booking->guest_name }},</p>
    <p>{{ $copy['id'] }}</p>

    <p style="margin-top: 24px;">
        <a href="{{ route('bookings.show', $booking) }}" style="color: #ea580c;">View booking / Lihat pesanan</a><br>
        <a href="https://wa.me/{{ config('booking.whatsapp_number') }}" style="color: #16a34a;">WhatsApp</a>
    </p>
</body>
</html>
