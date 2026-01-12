<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class CategoriesProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = 
        [
            [
                'name' => 'Electronics',
                'products' => [
                    [
                        'name' => 'Product 1',
                        'price' => 100,
                        'quantity' => 10,
                    ],
                    [
                        'name' => 'Product 2',
                        'price' => 200,
                        'quantity' => 20,
                    ],
                    [
                        'name' => 'Product 3',
                        'price' => 300,
                        'quantity' => 30,
                    ],
                ],
            ],
            [
                'name' => 'Clothing',
                'products' => [
                    [
                        'name' => 'Product 4',
                        'price' => 400,
                        'quantity' => 40,
                    ],
                ],
            ],
            [
                'name' => 'Books',
                'products' => [
                    [
                        'name' => 'Product 5',
                        'price' => 500,
                        'quantity' => 50,
                    ],
                ],
            ],
            [
                'name' => 'Furniture',
                'products' => [
                    [
                        'name' => 'Product 6',
                        'price' => 600,
                        'quantity' => 60,
                    ],
                ],
            ],
            [
                'name' => 'Other',
                'products' => [
                    [
                        'name' => 'Product 7',
                        'price' => 700,
                        'quantity' => 70,
                    ],
                ],
            ],
        ];
        foreach ($categories as $category) {
            $category = Category::create(['name' => $category['name']]);
            foreach ($category['products'] as $product) {
                $category->products()->create($product);
            }
        }
    }
}
