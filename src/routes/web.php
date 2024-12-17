<?php

declare(strict_types=1);

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', DashboardController::class)->name('dashboard');

require __DIR__ . '/auth.php';
require __DIR__ . '/invitation.php';
require __DIR__ . '/anime.php';
require __DIR__ . '/profile.php';
require __DIR__ . '/integration.php';
