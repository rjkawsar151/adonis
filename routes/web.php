<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PriceListController;
use App\Http\Controllers\Admin\BarberController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Api\FrontendController;

// ─── Public API Routes ───
Route::get('/api/services', [FrontendController::class, 'services']);
Route::get('/api/barbers', [FrontendController::class, 'barbers']);
Route::get('/api/blogs', [FrontendController::class, 'blogs']);
Route::get('/api/price-list/{branch?}', [FrontendController::class, 'priceList']);

// ─── Admin Authentication ───
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

// ─── Admin Protected Routes ───
Route::prefix('admin')->middleware(['web', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');



    // Appointments
    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('admin.appointments.index');
    Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('admin.appointments.show');
    Route::put('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])->name('admin.appointments.status');
    Route::delete('/appointments/{appointment}', [AdminAppointmentController::class, 'destroy'])->name('admin.appointments.destroy');

    // FAQs
    Route::get('/faqs', [FaqController::class, 'index'])->name('admin.faqs.index');
    Route::get('/faqs/create', [FaqController::class, 'create'])->name('admin.faqs.create');
    Route::post('/faqs', [FaqController::class, 'store'])->name('admin.faqs.store');
    Route::get('/faqs/{faq}/edit', [FaqController::class, 'edit'])->name('admin.faqs.edit');
    Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('admin.faqs.update');
    Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('admin.faqs.destroy');

    // Carousel
    Route::get('/carousel', [CarouselController::class, 'index'])->name('admin.carousel.index');
    Route::post('/carousel', [CarouselController::class, 'store'])->name('admin.carousel.store');
    Route::delete('/carousel/{id}', [CarouselController::class, 'destroy'])->name('admin.carousel.destroy');

    // Price List
    Route::get('/price-list', [PriceListController::class, 'index'])->name('admin.price-list.index');
    Route::get('/price-list/create', [PriceListController::class, 'create'])->name('admin.price-list.create');
    Route::post('/price-list', [PriceListController::class, 'store'])->name('admin.price-list.store');
    Route::get('/price-list/{id}/edit', [PriceListController::class, 'edit'])->name('admin.price-list.edit');
    Route::put('/price-list/{id}', [PriceListController::class, 'update'])->name('admin.price-list.update');
    Route::delete('/price-list/{id}', [PriceListController::class, 'destroy'])->name('admin.price-list.destroy');
    Route::post('/price-list/bulk', [PriceListController::class, 'bulkUpdate'])->name('admin.price-list.bulk');

    // Barbers
    Route::get('/barbers', [BarberController::class, 'index'])->name('admin.barbers.index');
    Route::get('/barbers/create', [BarberController::class, 'create'])->name('admin.barbers.create');
    Route::post('/barbers', [BarberController::class, 'store'])->name('admin.barbers.store');
    Route::get('/barbers/{barber}/edit', [BarberController::class, 'edit'])->name('admin.barbers.edit');
    Route::put('/barbers/{barber}', [BarberController::class, 'update'])->name('admin.barbers.update');
    Route::delete('/barbers/{barber}', [BarberController::class, 'destroy'])->name('admin.barbers.destroy');

    // Blogs
    Route::get('/blogs', [BlogController::class, 'index'])->name('admin.blogs.index');
    Route::get('/blogs/create', [BlogController::class, 'create'])->name('admin.blogs.create');
    Route::post('/blogs', [BlogController::class, 'store'])->name('admin.blogs.store');
    Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('admin.blogs.edit');
    Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('admin.blogs.update');
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('admin.blogs.destroy');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});

// ─── SPA Catch-All ───
Route::view('/{path?}', 'app')->where('path', '.*');
