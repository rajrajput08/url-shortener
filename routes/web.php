<?php

use App\Http\Controllers\AcceptInvitationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public: anyone can open a short url, no login needed.
Route::get('/s/{code}', [ShortUrlController::class, 'resolve'])->name('short-urls.resolve');

// Invited users set their password here, so they must not be logged in.
Route::middleware('guest')->group(function () {
    Route::get('/invitations/{token}/accept', [AcceptInvitationController::class, 'show'])->name('invitations.accept');
    Route::post('/invitations/{token}/accept', [AcceptInvitationController::class, 'store'])->name('invitations.accept.store');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::resource('short-urls', ShortUrlController::class)->only(['index', 'create', 'store']);
    Route::resource('invitations', InvitationController::class)->only(['index', 'create', 'store']);

    // management pages, only for the super admin
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('companies', CompanyController::class)->except('show');
        Route::resource('roles', RoleController::class)->except('show');
        Route::resource('permissions', PermissionController::class)->except('show');
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
