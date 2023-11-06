<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Models\Project;
use App\Models\User;

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

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});

// get all projects
Route::middleware('auth:sanctum')->get('/myprojects', [ProjectController::class, 'index']);
Route::middleware(['auth:sanctum', 'match_project'])->get('/myprojects/{project}', [ProjectController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('projects', ProjectController::class);
Route::apiResource('tasks', TaskController::class);

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);

Route::post('/transcribe', [AiController::class, 'transcribe']);
Route::post('/arabic', [AiController::class, 'arabicTransform']);
