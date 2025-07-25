<?php


// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarEventController;

Route::get('/calendar-events', [CalendarEventController::class, 'index']);
