<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});
Route::get('/isolated', function () { return view('isolated'); })->name('isolated');
