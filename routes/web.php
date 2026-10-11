
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('tasks.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tasks', TaskController::class);

    Route::patch('/tasks/{task}/complete', [TaskStatusController::class, 'complete'])->name('tasks.complete');
    Route::patch('/tasks/{task}/approve', [TaskStatusController::class, 'approve'])->name('tasks.approve');
    Route::patch('/tasks/{task}/reject', [TaskStatusController::class, 'reject'])->name('tasks.reject');
});

require __DIR__.'/auth.php';

