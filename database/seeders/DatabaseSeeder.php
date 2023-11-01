<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Folder;
use App\Models\Image;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $folders = [
            'My Postcard Collection',
            'Postcards For Swap',
            'Other Stuff'
        ];

        foreach ($folders as $name) {
            Folder::factory()->create([
                'slug' => strtolower(str_replace(' ', '-', $name)),
                'name' => $name,
            ]);
        }

        $folders = [
            [
                'id' => 4,
                'name' => 'Cats',
                'parent_id' => 1,
            ],
        ];

        foreach ($folders as $f) {
            Folder::factory()->create([
                'id' => $f['id'],
                'name' => $f['name'],
                'slug' => strtolower(str_replace(' ', '-', $f['name'])),
                'parent_id' => $f['parent_id'],
            ]);
        }
    }
}
