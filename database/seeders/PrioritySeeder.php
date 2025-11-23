<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrioritySeeder extends Seeder
{
    public function run()
    {
        DB::table('priorities')->upsert([
            ['name' => 'Rendah'],
            ['name' => 'Sedang'],
            ['name' => 'Tinggi'],
        ], ['name']);
    }
}
