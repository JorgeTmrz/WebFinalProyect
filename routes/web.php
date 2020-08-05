<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/home', function () {
    return view('master');
});

Route::get("/home","appController@index");
Route::get("/user","appController@user");
//Patient related
Route::get("/createPatient", "secretaryController@createpatient");
Route::get("/editp/{cedula}", "secretaryController@editp");
Route::get("/deletep/{cedula}", "secretaryController@deletep");
Route::post("/editedp", "secretaryController@editedp");
Route::post("/createp", "secretaryController@createp" );
//Cite related
Route::post("/createc", "secretaryController@createc");
Route::get("/cites", "secretaryController@Cites");
Route::get("/editc/{id}", "secretaryController@editc");
Route::post("/editedc", "secretaryController@editedc");
Route::get("/deletec/{id}", "secretaryController@deletec");
// Bithdays per month
Route::get("/birthdays","secretarycontroller@birthdays");
Route::post("/getbirthdays","secretarycontroller@getbirthdays");
// Pays
Route::get("/paydates", "secretarycontroller@paydates");
Route::post("/payedd", "secretarycontroller@payedd");
