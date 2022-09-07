<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Invoice;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// open api
Route::get('/invoice-list', [App\Http\Controllers\InvoiceController::class, 'invoice_list']);
Route::get('/invoice-detail/{id}', [App\Http\Controllers\InvoiceController::class, 'invoice_detail']);