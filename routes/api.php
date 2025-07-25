<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CalendarEventController;
use App\Http\Controllers\Api\BookingCalendarController;

Route::get('/calendar-events', [CalendarEventController::class, 'index']);

Route::get('/calendar-events', [BookingCalendarController::class, 'index']);
