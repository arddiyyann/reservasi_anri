<?php

use Illuminate\Support\Facades\Route;

// React SPA
Route::view('/login', 'app');
Route::view('/{any}', 'app')->where('any', '.*');
