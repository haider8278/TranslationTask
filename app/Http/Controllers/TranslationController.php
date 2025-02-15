<?php

namespace App\Http\Controllers;

use App\Repositories\TranslationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TranslationController extends Controller
{
    private TranslationRepositoryInterface $translationRepository;

    public function __construct(TranslationRepositoryInterface $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['locale', 'key']);
        return response()->json($this->translationRepository->getAll($filters));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'locale' => 'required|string',
            'key' => 'required|string|unique:translations,key',
            'content' => 'required|string'
        ]);

        return response()->json($this->translationRepository->create($data), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $translation = $this->translationRepository->findById($id);

        if (!$translation) {
            return response()->json(['message' => 'Translation not found'], 404);
        }

        $data = $request->validate(['content' => 'required|string']);
        return response()->json($this->translationRepository->update($translation, $data));
    }

    public function destroy(int $id): JsonResponse
    {
        $translation = $this->translationRepository->findById($id);

        if (!$translation) {
            return response()->json(['message' => 'Translation not found'], 404);
        }

        return response()->json(['deleted' => $this->translationRepository->delete($translation)]);
    }

    public function jsonExport(): JsonResponse
    {
        return response()->json($this->translationRepository->getJsonExport());
    }
}
