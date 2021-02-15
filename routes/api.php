<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('api')->post('/new-email', [\App\Http\Controllers\MailController::class, 'sendEmail']);
Route::middleware('api')->get('/get-emails', [\App\Http\Controllers\MailController::class, 'getEmails']);
Route::middleware('api')->get('/get-recipient-emails/{id}', [\App\Http\Controllers\MailController::class, 'getRecipientEmails']);