<?php

namespace App\Http\Controllers;

use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Repositories\NoteRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\UnauthorizedException;

class NoteController extends Controller
{
    public function __construct(
        private readonly NoteRepository $noteRepository
    )
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $notes = $this->noteRepository->all();

        return NoteResource::collection($notes);
    }

    public function store(Request $request): NoteResource
    {
        $this->validator($request);

        $note = new Note($request->all());
        $note->user_id = Auth::id();
        $note->save();
        $note->refresh();

        return new NoteResource($note);
    }

    public function show(Note $note): JsonResponse
    {
        $note = Note::query()->where('id', $note)->get();

        return response()->json($note, 200);
    }

    public function update(Request $request, Note $note): JsonResponse
    {
        if(Auth::id() !== $note->user_id) {
            throw new UnauthorizedException();
        }

        $this->validator($request);

        $updated = $note->update($request->all());

        return response()->json($updated, 201);
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

    private function validator(Request $request): void
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ])->validated();
    }
}
