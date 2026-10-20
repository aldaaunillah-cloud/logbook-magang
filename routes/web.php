<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\WeeklyReportController;
use App\Http\Controllers\DownloadWeeklyReportController;
use App\Http\Controllers\LearningController;
// Catatan kegiatan
Route::get('/', [ActivityController::class, 'index'])
    ->name('activities.index');

Route::post('/activities', [ActivityController::class, 'store'])
    ->name('activities.store');

Route::patch('/activities/{activity}', [ActivityController::class, 'update'])
    ->name('activities.update');

Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])
    ->name('activities.destroy');

Route::post('/activities/{activity}/polish', [ActivityController::class, 'polish'])
    ->name('activities.polish')
    ->middleware('throttle:6,1');

// Pratinjau laporan mingguan
Route::get('/laporan-mingguan', [WeeklyReportController::class, 'index'])
    ->name('reports.weekly');

// Unduh laporan Word
Route::get('/laporan-mingguan/word', DownloadWeeklyReportController::class)
    ->name('reports.weekly.word');

Route::get('/pahami-pekerjaan', [LearningController::class, 'index'])
    ->name('learning.index');

Route::post(
    '/pahami-pekerjaan/{activity}',
    [LearningController::class, 'generate']
)
    ->name('learning.generate')
    ->middleware('throttle:6,1');