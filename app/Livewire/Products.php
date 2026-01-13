<?php

namespace App\Livewire;

use Livewire\Component;

use App\Services\ProductsServices;

class Products extends Component
{
    public $products = [];

    public function mount()
    {
        \Log::info('Products component mounted');
        $this->products = ProductsServices::getProducts();
    }

    public function render()
    {
        return view('livewire.products');
    }
}
