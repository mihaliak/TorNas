<?php

use Illuminate\Database\Seeder;

class CreateGenres extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = [
            ['name' => 'Action', 'apiValue' => 'Action'],
            ['name' => 'Adventure', 'apiValue' => 'Adventure'],
            ['name' => 'Animation', 'apiValue' => 'Animation'],
            ['name' => 'Biography', 'apiValue' => 'Biography'],
            ['name' => 'Comedy', 'apiValue' => 'Comedy'],
            ['name' => 'Crime', 'apiValue' => 'Crime'],
            ['name' => 'Documentary', 'apiValue' => 'Documentary'],
            ['name' => 'Drama', 'apiValue' => 'Drama'],
            ['name' => 'Family', 'apiValue' => 'Family'],
            ['name' => 'Fantasy', 'apiValue' => 'Fantasy'],
            ['name' => 'Film-Noir', 'apiValue' => 'Film-Noir'],
            ['name' => 'History', 'apiValue' => 'History'],
            ['name' => 'Horror', 'apiValue' => 'Horror'],
            ['name' => 'Music', 'apiValue' => 'Music'],
            ['name' => 'Musical', 'apiValue' => 'Musical'],
            ['name' => 'Mystery', 'apiValue' => 'Mystery'],
            ['name' => 'Romance', 'apiValue' => 'Romance'],
            ['name' => 'Sci-Fi', 'apiValue' => 'Sci-Fi'],
            ['name' => 'Short', 'apiValue' => 'Short'],
            ['name' => 'Sport', 'apiValue' => 'Sport'],
            ['name' => 'Thriller', 'apiValue' => 'Thriller'],
            ['name' => 'War', 'apiValue' => 'War'],
            ['name' => 'Western', 'apiValue' => 'Western'],
        ];

        foreach ($genres as $genre) {
            \TorNas\Modules\Genre\Genre::create($genre);
        }
    }
}
