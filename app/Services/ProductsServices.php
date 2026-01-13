<?php

namespace App\Services;
use App\Models\Product;
use App\Models\Category;

class ProductsServices
{
    public static function getProducts(string $category = null)
    {
        $query = Product::query();
        
        if ($category) {
            $category = Category::where('name', 'like', '%' . $category . '%')->first();
            if (!$category) {
                throw new \Exception('Category not found');
            }
            $query->where('category_id', $category->id);
        }
        return $query->get();
    }

    public static function createProduct(string $name, float $price, int $quantity, string $category)
    {
        $category = Category::where('name', $category)->first();
        if (!$category) {
            throw new \Exception('Category not found');
        }
        return Product::create([
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'category_id' => $category->id,
        ]);
    }

    public static function getProductsByName(string $name)
    {
        return Product::where('name', 'like', '%' . $name . '%')->get();
    }

    public static function getAllCategories()
    {
        return Category::pluck('name');
    }
}