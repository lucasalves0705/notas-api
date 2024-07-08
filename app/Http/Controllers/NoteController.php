<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::query()->orderByDesc('created_at', 'desc')->get();

        return response()->json($notes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        $note = Note::query()->where('id', $note)->get();

        return response()->json($note, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();

        return response()->json(null, 204);
    }
}
