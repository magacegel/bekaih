<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

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

Route::get('api_report_all', [ReportController::class, 'api_report_all']);
Route::get('api_report/{id}', [ReportController::class, 'api_report']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
