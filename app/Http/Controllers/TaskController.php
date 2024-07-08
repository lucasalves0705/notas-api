<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\UnauthorizedException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::query()->orderByDesc('created_at')->get();

        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required'],
            'description' => ['required'],
            'date' => ['nullable', 'date:Y-m-d']
        ])->validate();

        $task = new Task($request->all());
        $task->finished = false;
        $task->user_id = Auth::id();
        $task->save();
        $task->refresh();

        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //$task = Task::query()->where('id', $task)->get();

        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if(Auth::id() !== $task->user_id) {
            throw new UnauthorizedException();
        }

        Validator::make($request->all(), [
            'name' => ['required'],
            'description' => ['required'],
            'date' => ['nullable', 'date:Y-m-d']
        ])->validate();

        $updated = $task->update($request->all());

        return response()->json($updated, 201);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(null, 204);
    }
    public function finished(Task $task)
    {
        $task->finished = true;
        $activated = $task->save();

        return response()->json($activated, 200);
    }

    public function unfinished(Task $task)
    {
        $task->finished = false;
        $deactivated = $task->save();

        return response()->json($deactivated, 200);
    }
}
