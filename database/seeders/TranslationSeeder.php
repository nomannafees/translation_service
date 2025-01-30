<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\Translation;
use Illuminate\Support\Facades\DB;

class TranslationSeeder extends Seeder
{
    public function run()
    {
        $tags = Tag::factory()->count(10)->create();
        Translation::factory()->count(100000)->create()->each(function ($translation) use ($tags) {
            $translation->tags()->attach($tags->random(rand(1, 3)));
        });
    }
}


