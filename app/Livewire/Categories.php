<?php

namespace App\Livewire;

use Livewire\Component;

use App\Services\ProductsServices;

class Categories extends Component
{
    public $categories = [];

    public function mount()
    {
        $this->categories = ProductsServices::getAllCategories();
    }

    public function render()
    {
        return view('livewire.categories');
    }
}
