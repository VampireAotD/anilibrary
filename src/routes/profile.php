<?php

declare(strict_types=1);

use App\Http\Controllers\Profile\ProfileController;

Route::group(['as' => 'profile.', 'controller' => ProfileController::class], function () {
    Route::get('/profile', 'edit')->name('edit');
    Route::patch('/profile', 'update')->name('update');
    Route::delete('/profile', 'destroy')->name('destroy');
});
