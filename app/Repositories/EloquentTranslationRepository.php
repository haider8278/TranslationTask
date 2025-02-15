<?php
namespace App\Repositories;

use App\Models\Translation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class EloquentTranslationRepository implements TranslationRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator
    {
        return Cache::remember('translations:all', 60, function () use ($filters) {
            $query = Translation::query();

            if (isset($filters['locale'])) {
                $query->where('locale', $filters['locale']);
            }

            if (isset($filters['key'])) {
                $query->where('key', 'like', "%{$filters['key']}%");
            }

            return $query->paginate(50);
        });
    }

    public function findById(int $id): ?Translation
    {
        return Cache::remember("translations:{$id}", 60, function () use ($id) {
            return Translation::find($id);
        });
    }

    public function create(array $data): Translation
    {
        $translation = Translation::create($data);
        Cache::forget('translations:all');
        return $translation;
    }

    public function update(Translation $translation, array $data): Translation
    {
        $translation->update($data);
        Cache::forget("translations:{$translation->id}");
        Cache::forget('translations:all');
        return $translation;
    }

    public function delete(Translation $translation): bool
    {
        Cache::forget("translations:{$translation->id}");
        Cache::forget('translations:all');
        return $translation->delete();
    }

    public function getJsonExport()
    {
        return Cache::remember('translations_json', 600, function () {
            return Translation::all();
        });
    }
}

