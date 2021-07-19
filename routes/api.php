<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;



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

Route::get("tasks", [TasksController::class, 'index']);
Route::get("tasksWeek", [TasksController::class, 'tasksWeek']);
Route::get("tasksMonth", [TasksController::class, 'tasksMonth']);
Route::post("tasks", [TasksController::class, 'create']);
Route::delete("tasks/{id}",[TasksController::class, 'destroy']);
Route::post("tasks/{id}/update",[TasksController::class, 'update']);
Route::get("tasks/{id}",[TasksController::class, 'updateStatus']);
Route::get("taskshow/{id}",[TasksController::class, 'show']);