<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreatePostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            [
                'website_id'=>1,
                'title'=>'My Haunted House',
                'contents'=>"Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam magnam ducimus vero, voluptate enim, hic aspernatur ex velit doloribus iste odit ipsam nesciunt, nam numquam quasi fugiat aut suscipit assumenda."
            ],
        ]);
    }
}
