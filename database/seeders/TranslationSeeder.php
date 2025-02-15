<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Translation;
use Faker\Factory as Faker;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100000; $i++) {
            Translation::create([
                'locale' => $faker->randomElement(['en', 'fr', 'es']),
                'key' => $faker->word,
                'content' => $faker->sentence,
            ]);
        }
    }
}
