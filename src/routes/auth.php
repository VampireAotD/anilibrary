<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegistrationAccessController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware(['auth', 'verified'])->middleware('guest')->group(function () {
    Route::group(
        [
            'as'         => 'registration_access.',
            'prefix'     => 'register',
            'controller' => RegistrationAccessController::class,
            'middleware' => 'throttle:6,1',
        ],
        function () {
            Route::get('await', 'show')->name('await');

            Route::get('request', 'create')->name('request');

            Route::post('request', 'store')->name('acquire');
        }
    );

    Route::controller(RegistrationController::class)->group(function () {
        Route::get('register', 'create')
             ->middleware(['signed', 'registration.has_invitation'])
             ->name('register');

        Route::post('register', 'store');
    })->middleware('throttle:6,1');

    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'create')->name('login');

        Route::post('login', 'store');
    });

    Route::controller(PasswordResetLinkController::class)->group(function () {
        Route::get('forgot-password', 'create')->name('password.request');

        Route::post('forgot-password', 'store')->name('password.email');
    });

    Route::controller(NewPasswordController::class)->group(function () {
        Route::get('reset-password/{token}', 'create')->name('password.reset');

        Route::post('reset-password', 'store')->name('password.store');
    });
});

Route::group(
    [
        'as'                  => 'verification.',
        'name'                => 'email-verification',
        'excluded_middleware' => 'verified',
    ],
    function () {
        Route::get('verify-email', EmailVerificationPromptController::class)->name('notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
             ->middleware(['signed', 'throttle:6,1'])
             ->name('verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
             ->middleware('throttle:6,1')
             ->name('send');
    }
);

Route::controller(ConfirmablePasswordController::class)->group(function () {
    Route::get('confirm-password', 'show')->name('password.confirm');

    Route::post('confirm-password', 'store');
});

Route::put('password', [PasswordController::class, 'update'])->name('password.update');

Route::post('logout', [LoginController::class, 'destroy'])
     ->withoutMiddleware('verified')
     ->name('logout');
