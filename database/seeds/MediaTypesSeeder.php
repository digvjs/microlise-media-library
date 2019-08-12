<?php

use Illuminate\Database\Seeder;
use App\MediaType;

class MediaTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MediaType::updateOrCreate(['id' => 1], ['id' => 1, 'type' => 'video', 'alias' => 'Videos']);
        MediaType::updateOrCreate(['id' => 2], ['id' => 2, 'type' => 'images', 'alias' => 'Images']);
        MediaType::updateOrCreate(['id' => 3], ['id' => 3, 'type' => 'documents', 'alias' => 'Documents']);
        MediaType::updateOrCreate(['id' => 4], ['id' => 4, 'type' => 'mp3', 'alias' => 'MP3']);
        MediaType::updateOrCreate(['id' => 5], ['id' => 5, 'type' => 'links', 'alias' => 'Other Channels']);

    }
}
