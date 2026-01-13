<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Services\ProductsServices;
use Barryvdh\DomPDF\Facade\Pdf;

class FilesServices
{
    public static function downloadCategoriesFile()
    {
        $categories = ProductsServices::getAllCategories();
        $pdf = Pdf::loadView('pdf.categories', ['categories' => $categories]);
        return $pdf->download('categories.pdf');
    }

    public static function downloadProductsFile()
    {
        $products = ProductsServices::getProducts();
        $pdf = Pdf::loadView('pdf.products', ['products' => $products]);
        return $pdf->download('products.pdf');
    }
}