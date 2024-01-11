<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\smsprocontroller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[smsprocontroller::class,'main'])->name('main');
Route::get('/newmain',[smsprocontroller::class,'newmain'])->name('newmain');
Route::get('/smsproalert',[smsprocontroller::class,'smsproalert'])->name('smsproalert');



Route::get('/wel', function () {
    return view('welcome');
});