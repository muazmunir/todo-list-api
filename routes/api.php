<?php

use App\Http\Controllers\ToDoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/verify', [UserController::class, 'verify']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('jwt.verify')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/todos', [ToDoController::class, 'index']);
    Route::post('/todos', [ToDoController::class, 'store']);
    Route::get('/todos/{todo}', [ToDoController::class, 'show']);
    Route::put('/todos/{todo}', [ToDoController::class, 'update']);
    Route::delete('/todos/{todo}', [ToDoController::class, 'destroy']);
});
