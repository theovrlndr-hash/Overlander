<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\WishlistController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])
    ->whereIn('locale', ['en', 'id'])
    ->name('locale.switch');

Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{destination}', [DestinationController::class, 'show'])->name('destinations.show');

Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package}', [PackageController::class, 'show'])->name('packages.show');
Route::get('/packages/{package}/availability', [PackageController::class, 'availability'])->name('packages.availability');
Route::get('/packages/{package}/availability-month', [PackageController::class, 'availabilityMonth'])->name('packages.availability.month');

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/about', fn () => view('about'))->name('about');
Route::get('/faq', fn () => view('faq'))->name('faq');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/contact', fn () => view('contact'))->name('contact');
Route::get('/privacy', fn () => view('privacy'))->name('privacy');
Route::get('/terms', fn () => view('terms'))->name('terms');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::post('/newsletter', [NewsletterController::class, 'store'])->middleware('throttle:5,1')->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/auth/{provider}', [SocialController::class, 'redirect'])->name('auth.social');

    Route::get('/forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'form'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::get('/auth/{provider}/callback', [SocialController::class, 'callback'])->name('auth.social.callback');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Email verification
Route::get('/email/verify', fn () => view('auth.verify-email'))
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    abort_unless(hash_equals(sha1($user->getEmailForVerification()), (string) $hash), 403);

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login')->with('success', __('flash.email_verified'));
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/resend', function (Request $request) {
    try {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', __('flash.verification_resent'));
    } catch (\Exception $e) {
        \Log::error('Email verification resend failed: ' . $e->getMessage());
        return back()->with('error', __('flash.verification_send_failed'));
    }
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Profile settings — for any logged-in user
Route::middleware(['auth', 'verified'])->prefix('settings')->name('settings.')->group(function () {
    Route::get('/profile', [Settings\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Settings\ProfileController::class, 'update'])->name('profile.update');
});

// Member: bookings & reviews — login required (per spec, non-members can only view)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{package}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/custom/create', [BookingController::class, 'createCustom'])->name('bookings.create-custom');
    Route::post('/bookings/custom', [BookingController::class, 'storeCustom'])->name('bookings.store-custom');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/packages/{package}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/packages/{package}/book', [BookingController::class, 'store'])->name('bookings.store');

    Route::post('/packages/{package}/cart', [CartController::class, 'store'])->name('cart.store');
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    Route::delete('/cart', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::post('/destinations/{destination}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', Admin\CategoryController::class)->except(['show']);
    Route::resource('destinations', Admin\DestinationController::class)->except(['show']);
    Route::resource('packages', Admin\PackageController::class)->except(['show']);
    Route::resource('articles', Admin\ArticleController::class)->except(['show']);
    Route::resource('events', Admin\EventController::class)->except(['show']);

    Route::get('/bookings', [Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/calendar', [Admin\BookingController::class, 'calendar'])->name('bookings.calendar');
    Route::patch('/bookings/{booking}/status', [Admin\BookingController::class, 'updateStatus'])->name('bookings.status');

    Route::get('/reviews', [Admin\ReviewAdminController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/toggle-featured', [Admin\ReviewAdminController::class, 'toggleFeatured'])->name('reviews.toggle-featured');
    Route::delete('/reviews/{review}', [Admin\ReviewAdminController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/users', [Admin\UserAdminController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [Admin\UserAdminController::class, 'destroy'])->name('users.destroy');

    Route::get('/subscribers', [Admin\SubscriberAdminController::class, 'index'])->name('subscribers.index');
    Route::get('/subscribers/export', [Admin\SubscriberAdminController::class, 'export'])->name('subscribers.export');
    Route::delete('/subscribers/{subscriber}', [Admin\SubscriberAdminController::class, 'destroy'])->name('subscribers.destroy');
});
