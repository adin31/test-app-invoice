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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::group([
    'middleware' => ['auth']
], function (){
    Route::get('/', [App\Http\Controllers\InvoiceController::class, 'index'])->name('root');
    Route::get('home', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoice.home');
    Route::get('invoice', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('invoice/{invoice}/delete', [App\Http\Controllers\InvoiceController::class, 'destroy'])->name('invoice.delete');
    Route::post('invoice/fetch_item', [App\Http\Controllers\InvoiceController::class, 'fetch_item'])->name('invoice.fetch_item');
    Route::resource('invoice', App\Http\Controllers\InvoiceController::class);
});