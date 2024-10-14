<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageHeader;

class HomepageHeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HomepageHeader::create([
            'title' => 'Welcome to Our Store!',
            'subtitle' => 'Your one-stop shop for quality products.',
            'buttonText' => 'Shop Now',
            'image' => 'images/homepage_headers/default.jpg', // Replace with the actual image path
        ]);
    }
}
