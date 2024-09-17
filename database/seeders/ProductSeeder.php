<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product; // Assuming you have a Product model
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a Faker instance
        $faker = Faker::create();

        // Assuming you have existing subcategories in your 'subcategories' table
        $subcategories = \DB::table('subcategories')->pluck('id')->toArray();

        // Seed 50 products
        foreach (range(1, 500) as $index) {
            Product::create([
                'subcategory_id' => $faker->randomElement($subcategories), // Random subcategory
                'brand' => $faker->company, // Brand name (company name)
                'model' => strtoupper($faker->bothify('??###')), // Random model number like "AB123"
                'common_name' => $faker->word, // Single word as common name
                'description' => $faker->sentence(10), // Product description
                'product_code' => strtoupper($faker->unique()->bothify('PROD-####')), // Unique product code
                'price' => $faker->randomFloat(2, 100, 1000), // Random price between 100 and 1000
            ]);
        }
    }
}
