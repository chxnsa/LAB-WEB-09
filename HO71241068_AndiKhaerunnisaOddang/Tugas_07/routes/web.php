<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PariwisataController;

Route::get('/', [PariwisataController::class, 'home']);
Route::get('/destinasi', [PariwisataController::class, 'destinasi']);
Route::get('/kuliner', [PariwisataController::class, 'kuliner']);
Route::get('/galeri', [PariwisataController::class, 'galeri']);
Route::get('/kontak', [PariwisataController::class, 'kontak']);