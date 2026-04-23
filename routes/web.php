<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login'); // Ganti 'index' dengan nama file blade utamamu
});

Route::get('/login', function () {
    return view('login');
});

// Tambahkan baris ini
Route::get('/register', function () {
    return view('register');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});