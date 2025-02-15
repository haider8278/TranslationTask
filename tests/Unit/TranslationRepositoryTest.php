<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\TranslationRepositoryInterface;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected TranslationRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(TranslationRepositoryInterface::class);
    }

    public function test_can_create_translation()
    {
        $translation = $this->repository->create([
            'locale' => 'en',
            'key' => 'greeting',
            'content' => 'Hello'
        ]);

        $this->assertDatabaseHas('translations', ['key' => 'greeting']);
    }

    public function test_can_update_translation()
    {
        $translation = Translation::factory()->create();

        $updatedTranslation = $this->repository->update($translation, ['content' => 'Updated Text']);

        $this->assertEquals('Updated Text', $updatedTranslation->content);
    }
}
