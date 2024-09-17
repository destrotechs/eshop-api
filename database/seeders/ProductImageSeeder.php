<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductImage;
use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\File;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        // Allowed image formats
        $allowedFormats = ['jpg', 'jpeg', 'png', 'jfif', 'webp'];

        // Get all product IDs
        $products = Product::pluck('id')->toArray();

        foreach ($products as $productId) {
            // Generate random file extension from allowed formats
            $extension = $faker->randomElement($allowedFormats);
            
            // Simulate uploading a random image and saving it with the same logic as the controller
            $imageUrl = $faker->imageUrl(640, 480, 'technics', true); // You can change the category as needed
            $imageContents = file_get_contents($imageUrl); // Fetch the image
            
            // Generate a unique image name using the time() function like in the controller
            $imageName = time() . uniqid() . '.' . $extension;
            $imagePath = public_path('images/' . $imageName);

            // Save the image to the public/images directory
            File::put($imagePath, $imageContents);

            // Create and save a new ProductImage entry
            $product = Product::find($productId);
            if ($product) {
                $productImage = new ProductImage();
                $productImage->img_url = '/images/' . $imageName; // Save relative path to the database
                
                $product->images()->save($productImage);
            }
        }
    }
}
