<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    public function index(): JsonResponse
    {
        $notes = Note::query()
            ->with('folder.shared')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($notes, 200);
    }

    public function store(Request $request): JsonResponse
    {

        Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ])->validated();

        $note = new Note($request->all());
        $note->user_id = Auth::id();
        $note->save();
        $note->refresh();

        return response()->json($note, 200);
    }

    public function show(Note $note): JsonResponse
    {
        $note = Note::query()->where('id', $note)->get();

        return response()->json($note, 200);
    }

    public function update(Request $request, Note $note)
    {
        //
    }

    public function destroy(Note $note): JsonResponse
    {
//        dd($note);
        $note->delete();
        return response()->json(null, 204);
    }

    public function toggleImportant(Note $note): JsonResponse
    {
        $success = $note->update(['important' => !$note->important]);
        return response()->json($success);
    }
}
