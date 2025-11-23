<?php

use App\Http\Controllers\AssignmentsController;
use App\Livewire\Assignments\Create;
use App\Models\JoinGroup;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;



Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [\App\Http\Controllers\AssignmentsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/search', [AssignmentsController::class, 'search'])
    ->middleware(['auth', 'verified'])
    ->name('search');

//Route::get('/join-group/{key?}', Joingroup::class)->name('join.group');
Route::get('/group/{group}', \App\Livewire\Group\Groups::class)->name('group.show');
Route::get('/tasks', \App\Livewire\Assignment\CombinedTasks::class)->name('tasks');

//Route::get('/create', \App\Livewire\Assignments\Create::class)->name('store');

//Route::post('/dashboard', [AssignmentsController::class, 'store'])->name('store');

Route::get('/create', Create::class)->name('store');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

//Route::get('dashboard', function (){
//    $assignments = Auth::user()->assignments;
//    return view('dashboard', ['assignments' => $assignments]);
//})->name('dashboard')->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
