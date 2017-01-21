<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CreateGenres::class);
        $this->call(CreateCategories::class);
        $this->call(CreateAdminAccount::class);
    }
}
