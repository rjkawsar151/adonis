<?php

use App\Http\Controllers\Api\AdonisController;
use Illuminate\Support\Facades\Route;

Route::get('/data', [AdonisController::class, 'data']);

Route::put('/settings', [AdonisController::class, 'updateSettings']);
Route::put('/smtp', [AdonisController::class, 'updateSmtp']);
Route::get('/smtp/status', [AdonisController::class, 'smtpStatus']);
Route::post('/smtp/test', [AdonisController::class, 'testSmtp']);

Route::post('/services', [AdonisController::class, 'storeService']);
Route::put('/services/{id}', [AdonisController::class, 'updateService']);
Route::delete('/services/{id}', [AdonisController::class, 'deleteService']);

Route::post('/barbers', [AdonisController::class, 'storeBarber']);
Route::put('/barbers/{id}', [AdonisController::class, 'updateBarber']);
Route::delete('/barbers/{id}', [AdonisController::class, 'deleteBarber']);

Route::get('/blogs', [AdonisController::class, 'blogs']);
Route::post('/blogs', [AdonisController::class, 'storeBlog']);
Route::put('/blogs/{id}', [AdonisController::class, 'updateBlog']);
Route::delete('/blogs/{id}', [AdonisController::class, 'deleteBlog']);

Route::post('/upload', [AdonisController::class, 'upload']);

Route::post('/bookings', [AdonisController::class, 'storeBooking']);
Route::get('/bookings', [AdonisController::class, 'bookings']);
