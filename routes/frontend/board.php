<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\Frontend\User\AccountController;
use App\Http\Controllers\Frontend\User\DashboardController;
use App\Http\Controllers\Frontend\User\ProfileController;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 * These routes can not be hit if the user has not confirmed their email
 */
Route::group(['middleware' => ['auth', 'password.expires', config('boilerplate.access.middleware.verified')]], function () {
    Route::resource('board', BoardController::class);
    Route::get('board/{board}/sendStatus', [BoardController::class, 'sendStatus'])->name('board.sendStatus');
});
Route::get('board/updateActivity/{boardExternalId}', [BoardController::class, 'updateActivity']);
