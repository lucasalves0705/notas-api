<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\UnauthorizedException;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = Task::query()
            ->with('steps', 'folder.shared')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($tasks, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $this->validator($request);

        $task = new Task($request->all());
        $task->user_id = Auth::id();
        $task->save();

        $task->steps()->createMany($request->steps);

        $task->refresh();

        return response()->json($task, 201);
    }

    public function show(Task $task): JsonResponse
    {
        return response()->json($task);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        if(Auth::id() !== $task->user_id) {
            throw new UnauthorizedException();
        }

        $this->validator($request);

        $updated = $task->update($request->all());

        return response()->json($updated, 201);
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json(null, 204);
    }

    public function toggleImportant(Task $task): JsonResponse
    {
        $success = $task->update(['important' => !$task->important]);
        return response()->json($success);
    }


    private function validator(Request $request): void
    {
        Validator::make($request->all(), [
            'title' => ['required'],
            'description' => ['required'],
            'deadline' => ['nullable', 'date:Y-m-d'],
            'steps' => ['required', 'array'],
        ])->validate();
    }
}
