<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Public;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes — no auth needed
|--------------------------------------------------------------------------
*/

Route::post('/quotes',      [Public\QuoteController::class, 'store']);
Route::get('/tours',        [Public\TourController::class, 'index']);
Route::get('/tours/{slug}', [Public\TourController::class, 'show']);
Route::get('/settings',                      [Admin\SettingsController::class, 'index']);
Route::get('/testimonials',                  [Public\TestimonialController::class, 'index']);
Route::get('/blog',                          [Public\BlogController::class, 'index']);
Route::get('/blog/{blogPost:slug}',          [Public\BlogController::class, 'show']);
Route::get('/blog/{blogPost:slug}/comments', [Public\CommentController::class, 'index']);
Route::post('/blog/{blogPost:slug}/comments',[Public\CommentController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Admin Auth — public (no JWT needed)
|--------------------------------------------------------------------------
*/

Route::post('/admin/login',   [AdminAuthController::class, 'login']);
Route::post('/admin/refresh', [AdminAuthController::class, 'refresh']);

/*
|--------------------------------------------------------------------------
| Admin Protected — JWT required, admin guard
|--------------------------------------------------------------------------
*/

Route::middleware('auth:admin')->prefix('admin')->group(function () {

    // Auth
    Route::post('/logout',  [AdminAuthController::class, 'logout']);
    Route::get('/me',       [AdminAuthController::class, 'me']);
    Route::put('/me',       [AdminAuthController::class, 'updateMe']);

    // Dashboard
    Route::get('/dashboard/stats', [Admin\DashboardController::class, 'stats']);

    // Quotes
    Route::get('/quotes',            [Admin\QuoteController::class, 'index']);
    Route::get('/quotes/{quote}',    [Admin\QuoteController::class, 'show']);
    Route::patch('/quotes/{quote}',  [Admin\QuoteController::class, 'update']);
    Route::delete('/quotes/{quote}', [Admin\QuoteController::class, 'destroy']);

    // Tours
    Route::get('/tours',                  [Admin\TourController::class, 'index']);
    Route::post('/tours',                 [Admin\TourController::class, 'store']);
    Route::get('/tours/{tour}',           [Admin\TourController::class, 'show']);
    Route::put('/tours/{tour}',           [Admin\TourController::class, 'update']);
    Route::delete('/tours/{tour}',        [Admin\TourController::class, 'destroy']);
    Route::patch('/tours/{tour}/publish', [Admin\TourController::class, 'togglePublish']);
    Route::post('/tours/{tour}/images',   [Admin\TourController::class, 'uploadImage']);

    // Blog
    Route::get('/blog',                      [Admin\BlogController::class, 'index']);
    Route::post('/blog',                     [Admin\BlogController::class, 'store']);
    Route::get('/blog/{blogPost}',           [Admin\BlogController::class, 'show']);
    Route::put('/blog/{blogPost}',           [Admin\BlogController::class, 'update']);
    Route::delete('/blog/{blogPost}',        [Admin\BlogController::class, 'destroy']);
    Route::patch('/blog/{blogPost}/publish', [Admin\BlogController::class, 'togglePublish']);
    Route::post('/blog/{blogPost}/cover',    [Admin\BlogController::class, 'uploadCover']);

    // Testimonials
    Route::get('/testimonials',                              [Admin\TestimonialController::class, 'index']);
    Route::post('/testimonials',                             [Admin\TestimonialController::class, 'store']);
    Route::put('/testimonials/{testimonial}',                [Admin\TestimonialController::class, 'update']);
    Route::delete('/testimonials/{testimonial}',             [Admin\TestimonialController::class, 'destroy']);
    Route::post('/testimonials/{testimonial}/avatar',        [Admin\TestimonialController::class, 'uploadAvatar']);
    Route::patch('/testimonials/reorder',                    [Admin\TestimonialController::class, 'reorder']);

    // Bookings
    Route::get('/bookings',              [Admin\BookingController::class, 'index']);
    Route::post('/bookings',             [Admin\BookingController::class, 'store']);
    Route::get('/bookings/{booking}',    [Admin\BookingController::class, 'show']);
    Route::patch('/bookings/{booking}',  [Admin\BookingController::class, 'update']);
    Route::delete('/bookings/{booking}', [Admin\BookingController::class, 'destroy']);

    // Settings
    Route::get('/settings',   [Admin\SettingsController::class, 'index']);
    Route::patch('/settings', [Admin\SettingsController::class, 'update']);
});