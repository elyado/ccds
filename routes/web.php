<?php

use App\Http\Controllers\PublicCarteleraController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cartelera', [PublicCarteleraController::class, 'index'])
    ->name('cartelera.index');

Route::get('/cartelera/{slug}', [PublicCarteleraController::class, 'show'])
    ->name('cartelera.show');

Route::post(
    '/cartelera/{slug}/funciones/{schedule}/reservar',
    [PublicCarteleraController::class, 'storeReservation']
)->name('cartelera.reservations.store');

Route::get(
    '/cartelera/{slug}/reserva/{reservation}',
    [PublicCarteleraController::class, 'reservationSuccess']
)->name('cartelera.reservation.success');