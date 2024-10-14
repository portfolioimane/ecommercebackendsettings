<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductVariant;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        ProductVariant::create([
            'product_id' => 1, // Make sure this product exists in your products table
            'color' => 'Red',
            'size' => 'M',
            'price_adjustment' => 50,
            'image_url' => 'https://dummyimage.com/750x550/996633/fff',
        ]);

        ProductVariant::create([
            'product_id' => 1,
            'color' => 'Blue',
            'size' => 'L',
            'price_adjustment' => 100,
            'image_url' => 'https://dummyimage.com/100x100/6699cc/000',
        ]);

        ProductVariant::create([
            'product_id' => 1,
            'color' => null, // No color specified
            'size' => 'S',
            'price_adjustment' => 30,
            'image_url' => 'https://dummyimage.com/300x250/663366/fff',
        ]);

        ProductVariant::create([
            'product_id' => 1,
            'color' => 'Green',
            'size' => null, // No size specified
            'price_adjustment' => 20,
            'image_url' => 'https://dummyimage.com/400x300/00ff00/000',
        ]);
    }
}
