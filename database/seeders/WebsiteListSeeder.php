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
        DB::table('websites')->insert([
            [
                'name' => 'Alex\'s Blog',
                'description' => 'Hi! I am Alex. I will regularly post about my life on this website. Stay tuned.',
            ],
            [
                'name' => 'Sarah\'s Blog',
                'description'=>"Hi! Sarah here.... I will regulary post about my pet Tommy! Stay tuned...."
            ]
        ]);
    }
}
