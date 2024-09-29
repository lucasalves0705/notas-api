<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Repositories\NoteRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
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

    public function store(NoteRequest $request): NoteResource|JsonResponse
    {
        $note = $this->noteRepository->store($request->all());

        return (new NoteResource($note))->response()->setStatusCode(201);
    }

    public function show($note): NoteResource|JsonResponse
    {
        $note = $this->noteRepository->find($note);

        if ($note) {
            return new NoteResource($note);
        }

        return response()->json($note, 404);
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
}
