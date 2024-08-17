<?php

use App\Livewire\Dashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\CreateUser;
use App\Livewire\Home;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', Home::class);

// Route to show email verification notice page
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Route to handle email verification link
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Route to send a new email verification notification
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/register', Register::class)->name('register');
Route::get('/login', Login::class)->name('login');

// Route for user dashboard, accessible only to authenticated and verified users
Route::get('/dashboard', Dashboard::class)->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/users/create', CreateUser::class)->middleware(['auth'])->name('users.create');
Route::get('/users/edit/{userId}', CreateUser::class)->middleware(['auth'])->name('users.edit');
