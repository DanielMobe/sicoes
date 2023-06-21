<?php

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

Route::get('/', function () {
    return view('init');
});

Route::get('/init', function () {
    return view('init');
});

Route::get('/pdf/downloader', function () {
    return view('pdfDownloader');
});

Route::get('kardex/download/{matricula?}', 'alumnosController@downloadKardex');
Route::get('boleta/download/{matricula?}', 'alumnosController@downloadBoleta');

Route::get('login/required', 'alumnosController@loginRequired');