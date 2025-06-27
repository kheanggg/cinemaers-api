<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = ['Action', 'Comedy', 'Drama', 'Fantasy', 'Horror'];

        foreach ($genres as $genre) {
            DB::table('genres')->insert(['name' => $genre]);
        }
    }
}
