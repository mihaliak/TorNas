<?php

use Illuminate\Database\Seeder;

class CreateCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \TorNas\Modules\Category\Category::create(['name' => 'Movie', 'directory' => 'movies', 'apiValue' => 'movie']);
        \TorNas\Modules\Category\Category::create(['name' => 'TV Show', 'directory' => 'shows', 'apiValue' => 'series']);
    }
}
