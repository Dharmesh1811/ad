<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/apply-online', function () {
    return view('apply-online');
});

Route::get('/track-status', function () {
    return view('track-status');
});

Route::get('/admit-card', function () {
    return view('admit-card');
});

