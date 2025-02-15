<?php
namespace App\Http\Controllers;

use App\Repositories\TranslationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Translation Management API",
 *      description="API for managing translations in multiple languages",
 *      @OA\Contact(email="haider8278@gmail.com"),
 * )
 *
 * @OA\Tag(
 *     name="Translations",
 *     description="Endpoints for managing translations"
 * )
 */



class TranslationController extends Controller
{
    private TranslationRepositoryInterface $translationRepository;

    public function __construct(TranslationRepositoryInterface $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/translations",
     *      tags={"Translations"},
     *      summary="Retrieve a list of translations",
     *      description="Returns a paginated list of translations with optional filters.",
     *      security={{ "sanctum" }},
     *      @OA\Parameter(
     *          name="locale",
     *          in="query",
     *          description="Filter by locale (e.g., en, fr, es)",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="key",
     *          in="query",
     *          description="Filter by translation key",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(response=200, description="Successful response"),
     *      @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['locale', 'key']);
        return response()->json($this->translationRepository->getAll($filters));
    }
    /**
     * @OA\Post(
     *      path="/api/translations",
     *      tags={"Translations"},
     *      summary="Create a new translation",
     *      security={{ "bearerAuth": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"locale", "key", "content"},
     *              @OA\Property(property="locale", type="string", example="en"),
     *              @OA\Property(property="key", type="string", example="welcome_message"),
     *              @OA\Property(property="content", type="string", example="Welcome!")
     *          )
     *      ),
     *      @OA\Response(response=201, description="Translation created"),
     *      @OA\Response(response=400, description="Validation error"),
     *      @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'locale' => 'required|string',
            'key' => 'required|string|unique:translations,key',
            'content' => 'required|string'
        ]);

        return response()->json($this->translationRepository->create($data), 201);
    }
    /**
     * @OA\Put(
     *      path="/api/translations/{id}",
     *      tags={"Translations"},
     *      summary="Update an existing translation",
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Translation ID",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"content"},
     *              @OA\Property(property="content", type="string", example="Hello, world!")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Translation updated"),
     *      @OA\Response(response=404, description="Translation not found"),
     *      @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $translation = $this->translationRepository->findById($id);

        if (!$translation) {
            return response()->json(['message' => 'Translation not found'], 404);
        }

        $data = $request->validate(['content' => 'required|string']);
        return response()->json($this->translationRepository->update($translation, $data));
    }
    /**
     * @OA\Delete(
     *      path="/api/translations/{id}",
     *      tags={"Translations"},
     *      summary="Delete a translation",
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Translation ID",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(response=200, description="Translation deleted"),
     *      @OA\Response(response=404, description="Translation not found"),
     *      @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $translation = $this->translationRepository->findById($id);

        if (!$translation) {
            return response()->json(['message' => 'Translation not found'], 404);
        }

        return response()->json(['deleted' => $this->translationRepository->delete($translation)]);
    }
    /**
     * @OA\Get(
     *      path="/api/translations/json-export",
     *      tags={"Translations"},
     *      summary="Retrieve all translations in JSON format",
     *      description="Returns all translations optimized with Redis caching.",
     *      @OA\Response(response=200, description="Successful response"),
     *      @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function jsonExport(): JsonResponse
    {
        return response()->json($this->translationRepository->getJsonExport());
    }
}
