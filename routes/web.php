<?php

use App\Http\Controllers\PublicCarteleraController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::view('/el-soler', 'el-soler')->name('el-soler');

Route::get('/cartelera', [PublicCarteleraController::class, 'index'])
    ->name('cartelera.index');

Route::get('/cartelera/{slug}', [PublicCarteleraController::class, 'show'])
    ->name('cartelera.show');

Route::get(
    '/cartelera/{slug}/funciones/{schedule}/agenda.ics',
    [PublicCarteleraController::class, 'calendar']
)->name('cartelera.calendar');

Route::post(
    '/cartelera/{slug}/funciones/{schedule}/reservar',
    [PublicCarteleraController::class, 'storeReservation']
)->name('cartelera.reservations.store');

Route::get(
    '/cartelera/{slug}/reserva/{reservation}',
    [PublicCarteleraController::class, 'reservationSuccess']
)->name('cartelera.reservation.success');
