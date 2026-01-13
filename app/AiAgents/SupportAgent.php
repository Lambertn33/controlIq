<?php

namespace App\AiAgents;

use LarAgent\Agent;
use App\Services\ProductsServices;
use LarAgent\Attributes\Tool;
use App\Services\AuthServices;

class SupportAgent extends Agent
{
    protected $model = 'gpt-4.1-nano';

    protected $history = 'in_memory';

    protected $provider = 'default';

    public $user;

    public $isAdmin;

    public $isAuthenticated;

    protected $tools = [];

    public function __construct()
    {
        parent::__construct($this->provider);
        $this->initializeAuth();
    }

    protected function initializeAuth()
    {
        $authServicesCheck = AuthServices::checkIfUserIsAuthenticated();
        $authServicesAdmin = AuthServices::isUserAdmin();

        $this->isAuthenticated = $authServicesCheck['isAuthenticated'];
        $this->user = $authServicesCheck['user'];
        $this->isAdmin = $authServicesAdmin;
    }

    public function instructions()
    {
        // Ensure auth is always up-to-date when instructions are generated
        $this->initializeAuth();
        return view('prompts.support-agent-instructions', [
            'isAuthenticated' => $this->isAuthenticated,
            'user' => $this->user,
            'isAdmin' => $this->isAdmin,
        ])->render();
    }

    public function prompt($message)
    {
        return $message;
    }

    public function onInitialize()
    {
        $authServicesCheck = AuthServices::checkIfUserIsAuthenticated();
        $authServicesAdmin = AuthServices::isUserAdmin();

        $this->isAuthenticated = $authServicesCheck['isAuthenticated'];
        $this->user = $authServicesCheck['user'];
        $this->isAdmin = $authServicesAdmin;

        \Log::info('SupportAgent initialized', [
            'isAuthenticated' => $this->isAuthenticated,
            'user' => $this->user,
            'isAdmin' => $this->isAdmin,
        ]);
    }

    #[Tool('get the system categories in case the user wants to view them')]
    public function viewCategories()
    {
        return ProductsServices::getAllCategories();
    }

    #[Tool('get the system products. Use this when user asks to view products. Pass the category name (e.g., "Furniture", "Electronics") to filter by category, or omit the parameter to get all products')]
    public function viewProducts(?string $category = null)
    {
        try {
            if ($category) {
                return ProductsServices::getProducts($category);
            }
            return ProductsServices::getProducts();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }  
    
    #[Tool('search for a product by name. Use this when user asks to search for a product. Pass the product name to search for')]
    public function searchProduct(string $name)
    {
        return ProductsServices::getProductsByName($name);
    }
}
