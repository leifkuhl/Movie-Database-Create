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
    return redirect('/home');
});

Route::get('/manageAccounts',  'ManageAccountsController@index')->name('manageAccounts');
Route::get('/createAccounts',  'ManageAccountsController@createAccounts');
Route::get('/listAccounts',  'ManageAccountsController@listAccounts');
Route::get('/generateLoginList',  'ManageAccountsController@generateLoginList');
Route::get('/resetPassword',  'ManageAccountsController@resetPassword');

Route::get('/manageHosts', 'ManageHostsController@index')->name('manageHosts');
Route::get('/addHost', 'ManageHostsController@addHost');
Route::get('/removeHost', 'ManageHostsController@removeHost');


Route::get('/showGrants', 'ShowGrantsController@index' )->name('showGrants');
Route::get('/show', 'ShowGrantsController@showGrants' );

Route::get('/purge', 'PurgeDatabaseServerController@PurgeDatabaseServer');

Route::get('/purgeDatabaseServer','PurgeDatabaseServerController@index')->name('purgeDatabaseServer');