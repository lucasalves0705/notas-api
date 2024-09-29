<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\TaskController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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

Route::get('/version', function () {
    return response()->json(\Illuminate\Support\Facades\App::version().'FINAL2');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/notes', NoteController::class)
        ->names('notes');

    Route::post('/note/{note}/toggle-important', [NoteController::class, 'toggleImportant'])
        ->name('note.toggle-important');

    Route::apiresource('/tasks', TaskController::class);

    Route::post('/tasks/{task}/toggle-important', [TaskController::class, 'toggleImportant'])
        ->name('tasks.toggle-important');
});

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});
