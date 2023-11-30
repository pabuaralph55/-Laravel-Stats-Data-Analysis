<?php

use App\Http\Controllers\CountriesController;
use App\Http\Controllers\PlatformsController;
use App\Http\Controllers\PublishersController;
use App\Http\Controllers\StatsController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [StatsController::class, 'getOverallPublisherReport'])->name('reports.index');
Route::get('/dashboard/thirty_day', [StatsController::class, 'getThirtyDayReport'])->name('reports.thirty_day');
Route::get('/dashboard/performance_by_day', [StatsController::class, 'getPerformanceByDayReport'])->name('reports.performance_by_day');
