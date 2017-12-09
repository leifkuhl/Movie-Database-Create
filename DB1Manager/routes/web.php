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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function () {
    return view('home');
});

Route::get('test/{username}', function ($username) {
    return view('test', ['name' => $username]);
});



Route::get('/manageAccounts', function () {
     return view('manageAccounts');
})->name('manageAccounts')->middleware('auth');

Route::get('/manageHosts', function () {
     return view('manageHosts');
})->name('manageHosts')->middleware('auth');

Route::get('/show', function () {
     return redirect('/showGrants');
});
Route::get('/showGrants', function () {
     return view('showGrants');
})->name('showGrants')->middleware('auth');

Route::get('/purge', function () {
     return redirect('/purgeDatabaseServer');
});
Route::get('/purgeDatabaseServer', function () {
     return view('purgeDatabaseServer');
})->name('purgeDatabaseServer')->middleware('auth');