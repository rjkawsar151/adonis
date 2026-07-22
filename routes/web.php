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
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmploymentTypeController;
use App\Http\Controllers\Admin\CareerController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\FrontendController;

// ─── Public API Routes ───
Route::get('/api/services', [FrontendController::class, 'services']);
Route::get('/api/barbers', [FrontendController::class, 'barbers']);
Route::get('/api/blogs', [FrontendController::class, 'blogs']);
Route::get('/api/blogs/{slug}', [FrontendController::class, 'blogDetail']);
Route::get('/api/blog-archives/category/{slug}', [FrontendController::class, 'categoryArchive']);
Route::get('/api/blog-archives/tag/{slug}', [FrontendController::class, 'tagArchive']);
Route::get('/api/blog-archives/author/{id}', [FrontendController::class, 'authorArchive']);
Route::get('/sitemap.xml', [FrontendController::class, 'sitemapXml']);
Route::get('/api/price-list/{branch?}', [FrontendController::class, 'priceList']);
Route::get('/api/about', [FrontendController::class, 'about']);

// ─── Admin Authentication ───
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

// ─── Admin Protected Routes ───
Route::prefix('admin')->middleware(['web', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Admin Users Management (Super Admin & Admin only)
    Route::resource('/users', UserController::class, ['names' => 'admin.users'])->middleware('role:users');

    // Appointments
    Route::middleware('role:appointments')->group(function () {
        Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('admin.appointments.index');
        Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('admin.appointments.show');
        Route::put('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])->name('admin.appointments.status');
        Route::delete('/appointments/{appointment}', [AdminAppointmentController::class, 'destroy'])->name('admin.appointments.destroy');
        Route::post('/appointments/bulk-action', [AdminAppointmentController::class, 'bulkAction'])->name('admin.appointments.bulk-action');
    });

    // FAQs
    Route::middleware('role:faqs')->group(function () {
        Route::get('/faqs', [FaqController::class, 'index'])->name('admin.faqs.index');
        Route::get('/faqs/create', [FaqController::class, 'create'])->name('admin.faqs.create');
        Route::post('/faqs', [FaqController::class, 'store'])->name('admin.faqs.store');
        Route::get('/faqs/{faq}/edit', [FaqController::class, 'edit'])->name('admin.faqs.edit');
        Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('admin.faqs.update');
        Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('admin.faqs.destroy');
    });

    // Carousel
    Route::middleware('role:carousel')->group(function () {
        Route::get('/carousel', [CarouselController::class, 'index'])->name('admin.carousel.index');
        Route::post('/carousel', [CarouselController::class, 'store'])->name('admin.carousel.store');
        Route::delete('/carousel/{id}', [CarouselController::class, 'destroy'])->name('admin.carousel.destroy');
    });

    // Price List
    Route::middleware('role:price-list')->group(function () {
        Route::get('/price-list', [PriceListController::class, 'index'])->name('admin.price-list.index');
        Route::get('/price-list/create', [PriceListController::class, 'create'])->name('admin.price-list.create');
        Route::post('/price-list', [PriceListController::class, 'store'])->name('admin.price-list.store');
        Route::get('/price-list/{id}/edit', [PriceListController::class, 'edit'])->name('admin.price-list.edit');
        Route::put('/price-list/{id}', [PriceListController::class, 'update'])->name('admin.price-list.update');
        Route::delete('/price-list/{id}', [PriceListController::class, 'destroy'])->name('admin.price-list.destroy');
        Route::post('/price-list/bulk', [PriceListController::class, 'bulkUpdate'])->name('admin.price-list.bulk');
    });

    // Barbers
    Route::middleware('role:barbers')->group(function () {
        Route::get('/barbers', [BarberController::class, 'index'])->name('admin.barbers.index');
        Route::get('/barbers/create', [BarberController::class, 'create'])->name('admin.barbers.create');
        Route::post('/barbers', [BarberController::class, 'store'])->name('admin.barbers.store');
        Route::get('/barbers/{barber}/edit', [BarberController::class, 'edit'])->name('admin.barbers.edit');
        Route::put('/barbers/{barber}', [BarberController::class, 'update'])->name('admin.barbers.update');
        Route::delete('/barbers/{barber}', [BarberController::class, 'destroy'])->name('admin.barbers.destroy');
    });

    // Blogs (Accessible by Content Editor, Admin, Super Admin)
    Route::middleware('role:blogs')->group(function () {
        Route::get('/blogs', [BlogController::class, 'index'])->name('admin.blogs.index');
        Route::get('/blogs/create', [BlogController::class, 'create'])->name('admin.blogs.create');
        Route::post('/blogs', [BlogController::class, 'store'])->name('admin.blogs.store');
        Route::post('/blogs/bulk', [BlogController::class, 'bulkAction'])->name('admin.blogs.bulk');
        Route::post('/blogs/{blog}/duplicate', [BlogController::class, 'duplicate'])->name('admin.blogs.duplicate');
        Route::post('/blogs/{blog}/restore', [BlogController::class, 'restore'])->name('admin.blogs.restore');
        Route::delete('/blogs/{blog}/force-delete', [BlogController::class, 'forceDelete'])->name('admin.blogs.force-delete');
        Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('admin.blogs.edit');
        Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('admin.blogs.update');
        Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('admin.blogs.destroy');

        Route::resource('/blog-categories', \App\Http\Controllers\Admin\BlogCategoryController::class, ['names' => 'admin.blog-categories']);
        Route::resource('/blog-tags', \App\Http\Controllers\Admin\BlogTagController::class, ['names' => 'admin.blog-tags']);
        Route::resource('/blog-authors', \App\Http\Controllers\Admin\BlogAuthorController::class, ['names' => 'admin.blog-authors']);
    });

    // Settings
    Route::middleware('role:settings')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

        Route::get('/about-page', [AboutPageController::class, 'index'])->name('admin.about-page.index');
        Route::post('/about-page/chairman-message', [AboutPageController::class, 'saveChairmanMessage'])->name('admin.about-page.chairman');
        Route::post('/about-page/md-message', [AboutPageController::class, 'saveMdMessage'])->name('admin.about-page.md');
        Route::post('/about-page/company-intro', [AboutPageController::class, 'saveCompanyIntro'])->name('admin.about-page.intro');
        Route::post('/about-page/cta', [AboutPageController::class, 'saveCta'])->name('admin.about-page.cta');

        Route::post('/about-page/missions-visions', [AboutPageController::class, 'storeMissionVision'])->name('admin.about-page.missions-visions.store');
        Route::put('/about-page/missions-visions/{id}', [AboutPageController::class, 'updateMissionVision'])->name('admin.about-page.missions-visions.update');
        Route::delete('/about-page/missions-visions/{id}', [AboutPageController::class, 'destroyMissionVision'])->name('admin.about-page.missions-visions.destroy');

        Route::post('/about-page/core-values', [AboutPageController::class, 'storeCoreValue'])->name('admin.about-page.core-values.store');
        Route::put('/about-page/core-values/{id}', [AboutPageController::class, 'updateCoreValue'])->name('admin.about-page.core-values.update');
        Route::delete('/about-page/core-values/{id}', [AboutPageController::class, 'destroyCoreValue'])->name('admin.about-page.core-values.destroy');

        Route::post('/about-page/why-choose-us', [AboutPageController::class, 'storeWhyChooseUs'])->name('admin.about-page.why-choose-us.store');
        Route::put('/about-page/why-choose-us/{id}', [AboutPageController::class, 'updateWhyChooseUs'])->name('admin.about-page.why-choose-us.update');
        Route::delete('/about-page/why-choose-us/{id}', [AboutPageController::class, 'destroyWhyChooseUs'])->name('admin.about-page.why-choose-us.destroy');

        Route::post('/about-page/statistics', [AboutPageController::class, 'storeStatistic'])->name('admin.about-page.statistics.store');
        Route::put('/about-page/statistics/{id}', [AboutPageController::class, 'updateStatistic'])->name('admin.about-page.statistics.update');
        Route::delete('/about-page/statistics/{id}', [AboutPageController::class, 'destroyStatistic'])->name('admin.about-page.statistics.destroy');

        Route::post('/about-page/timelines', [AboutPageController::class, 'storeTimeline'])->name('admin.about-page.timelines.store');
        Route::put('/about-page/timelines/{id}', [AboutPageController::class, 'updateTimeline'])->name('admin.about-page.timelines.update');
        Route::delete('/about-page/timelines/{id}', [AboutPageController::class, 'destroyTimeline'])->name('admin.about-page.timelines.destroy');

        Route::post('/about-page/team-members', [AboutPageController::class, 'storeTeamMember'])->name('admin.about-page.team-members.store');
        Route::put('/about-page/team-members/{id}', [AboutPageController::class, 'updateTeamMember'])->name('admin.about-page.team-members.update');
        Route::delete('/about-page/team-members/{id}', [AboutPageController::class, 'destroyTeamMember'])->name('admin.about-page.team-members.destroy');

        Route::post('/about-page/reorder', [AboutPageController::class, 'reorder'])->name('admin.about-page.reorder');
    });

    // Careers & Recruitment (Accessible by HR, Admin, Super Admin)
    Route::middleware('role:careers')->group(function () {
        Route::get('/departments', [DepartmentController::class, 'index'])->name('admin.departments.index');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('admin.departments.store');
        Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('admin.departments.update');
        Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('admin.departments.destroy');
        Route::post('/departments/{id}/restore', [DepartmentController::class, 'restore'])->name('admin.departments.restore');
        Route::delete('/departments/{id}/force-delete', [DepartmentController::class, 'forceDelete'])->name('admin.departments.force-delete');

        Route::get('/employment-types', [EmploymentTypeController::class, 'index'])->name('admin.employment-types.index');
        Route::post('/employment-types', [EmploymentTypeController::class, 'store'])->name('admin.employment-types.store');
        Route::put('/employment-types/{id}', [EmploymentTypeController::class, 'update'])->name('admin.employment-types.update');
        Route::delete('/employment-types/{id}', [EmploymentTypeController::class, 'destroy'])->name('admin.employment-types.destroy');
        Route::post('/employment-types/{id}/restore', [EmploymentTypeController::class, 'restore'])->name('admin.employment-types.restore');
        Route::delete('/employment-types/{id}/force-delete', [EmploymentTypeController::class, 'forceDelete'])->name('admin.employment-types.force-delete');

        Route::get('/careers/applications', [ApplicationController::class, 'index'])->name('admin.applications.index');
        Route::get('/careers/applications/{application}', [ApplicationController::class, 'show'])->name('admin.applications.show');
        Route::patch('/careers/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('admin.applications.status');
        Route::patch('/careers/applications/{application}/notes', [ApplicationController::class, 'updateNotes'])->name('admin.applications.notes');
        Route::get('/careers/applications/{application}/download-cv', [ApplicationController::class, 'downloadCv'])->name('admin.applications.download-cv');
        Route::get('/careers/applications/{application}/view-cv', [ApplicationController::class, 'viewCv'])->name('admin.applications.view-cv');
        Route::delete('/careers/applications/{application}', [ApplicationController::class, 'destroy'])->name('admin.applications.destroy');
        Route::post('/careers/applications/{application}/restore', [ApplicationController::class, 'restore'])->name('admin.applications.restore');
        Route::delete('/careers/applications/{application}/force-delete', [ApplicationController::class, 'forceDelete'])->name('admin.applications.force-delete');
        Route::post('/careers/applications/bulk-action', [ApplicationController::class, 'bulkAction'])->name('admin.applications.bulk-action');

        Route::get('/careers', [CareerController::class, 'index'])->name('admin.careers.index');
        Route::get('/careers/create', [CareerController::class, 'create'])->name('admin.careers.create');
        Route::post('/careers', [CareerController::class, 'store'])->name('admin.careers.store');
        Route::get('/careers/{career}', [CareerController::class, 'show'])->name('admin.careers.show');
        Route::get('/careers/{career}/edit', [CareerController::class, 'edit'])->name('admin.careers.edit');
        Route::put('/careers/{career}', [CareerController::class, 'update'])->name('admin.careers.update');
        Route::delete('/careers/{career}', [CareerController::class, 'destroy'])->name('admin.careers.destroy');
        Route::post('/careers/{career}/restore', [CareerController::class, 'restore'])->name('admin.careers.restore');
        Route::delete('/careers/{career}/force-delete', [CareerController::class, 'forceDelete'])->name('admin.careers.force-delete');
    });

    // Memberships
    Route::middleware('role:memberships')->group(function () {
        Route::get('/memberships', [\App\Http\Controllers\Admin\MembershipController::class, 'index'])->name('admin.memberships.index');
        Route::put('/memberships/{membership}/status', [\App\Http\Controllers\Admin\MembershipController::class, 'updateStatus'])->name('admin.memberships.status');
        Route::delete('/memberships/{membership}', [\App\Http\Controllers\Admin\MembershipController::class, 'destroy'])->name('admin.memberships.destroy');
        Route::post('/memberships/bulk-action', [\App\Http\Controllers\Admin\MembershipController::class, 'bulkAction'])->name('admin.memberships.bulk-action');
    });
});

// ─── SPA Catch-All ───
// Standalone legal page (must be registered before the SPA catch-all).
Route::get('/privacy-policy', function () {
    return response()->file(base_path('privacy-policy.html'));
})->name('privacy-policy');

Route::redirect('/privacy-policy.html', '/privacy-policy', 301);
Route::redirect('/services', '/services/Gulshan', 301);
Route::redirect('/about', '/about-us', 301);

Route::view('/{path?}', 'app')->where('path', '.*');
