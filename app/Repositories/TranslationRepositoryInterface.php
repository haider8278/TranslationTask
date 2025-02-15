<?php
namespace App\Repositories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TranslationRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator;
    public function findById(int $id): ?Translation;
    public function create(array $data): Translation;
    public function update(Translation $translation, array $data): Translation;
    public function delete(Translation $translation): bool;
    public function getJsonExport();
}
