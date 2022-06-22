<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('websites')->insert([[
            'name' => 'Website 1',
            'description' => 'Alex\'s Blog',
        ],
        [
            'name' => 'Website 2',
            'description'=>NULL
        ]]);
    }
}
