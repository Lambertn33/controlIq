<?php

namespace App\AiAgents;

use LarAgent\Agent;
use App\Services\ProductsServices;
use LarAgent\Attributes\Tool;
use App\Services\AuthServices;
use App\Services\UserServices;
use App\Services\FilesServices;

class SupportAgent extends Agent
{
    protected $model = 'gpt-4.1-nano';

    protected $history = 'session';

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
    }

    #[Tool('get the system categories in case the user wants to view them')]
    public function viewCategories()
    {
        // Check authentication
        $this->initializeAuth();
        if (!$this->isAuthenticated) {
            return ['error' => 'Authentication required. Please log in to view categories.'];
        }
        
        return ProductsServices::getAllCategories();
    }

    #[Tool('get the system products. Use this when user asks to view products. Pass the category name (e.g., "Furniture", "Electronics") to filter by category, or omit the parameter to get all products')]
    public function viewProducts(?string $category = null)
    {
        // Check authentication
        $this->initializeAuth();
        if (!$this->isAuthenticated) {
            return ['error' => 'Authentication required. Please log in to view products.'];
        }
        
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
        // Check authentication
        $this->initializeAuth();
        if (!$this->isAuthenticated) {
            return ['error' => 'Authentication required. Please log in to search products.'];
        }
        
        return ProductsServices::getProductsByName($name);
    }

    #[Tool('create a new category. Use this ONLY when an admin user asks to create a new category. Pass the category name to create')]
    public function createCategory(string $name)
    {
        // Check authentication and admin status
        $this->initializeAuth();
        if (!$this->isAuthenticated) {
            return ['error' => 'Authentication required. Please log in to create categories.'];
        }
        if (!$this->isAdmin) {
            return ['error' => 'Access denied. Only administrators can create categories.'];
        }
        
        return ProductsServices::createCategory($name);
    }

    #[Tool('check if a category exists. Use this when user asks to check if a category exists. Pass the category name to check')]
    public function checkIfCategoryExists(string $name)
    {
        // Check authentication
        $this->initializeAuth();
        if (!$this->isAuthenticated) {
            return ['error' => 'Authentication required. Please log in to check categories.'];
        }
        
        return ProductsServices::checkIfCategoryExists($name);
    }

    #[Tool('check if a product exists. Use this when user asks to check if a product exists. Pass the product name to check')]
    public function checkIfProductExists(string $name)
    {
        // Check authentication
        $this->initializeAuth();
        if (!$this->isAuthenticated) {
            return ['error' => 'Authentication required. Please log in to check products.'];
        }
        
        return ProductsServices::checkIfProductExists($name);
    }

    #[Tool('create a new product. Use this ONLY when an admin user asks to create a new product. Pass the product name, price, quantity and category to create')]
    public function createProduct(string $name, float $price, int $quantity, string $category)
    {
        // Check authentication and admin status
        $this->initializeAuth();
        if (!$this->isAuthenticated) {
            return ['error' => 'Authentication required. Please log in to create products.'];
        }
        if (!$this->isAdmin) {
            return ['error' => 'Access denied. Only administrators can create products.'];
        }
        
        return ProductsServices::createProduct($name, $price, $quantity, $category);
    }

    #[Tool('get all users. Use this when an admin asks to get all users')]
    public function getAllUsers()
    {
        return UserServices::getAllUsers();
    }

    #[Tool('get a user by name. Use this when an admin asks to get a user by name')]
    public function getUserByName(string $name)
    {
        return UserServices::getUserByName($name);
    }

    #[Tool('download the categories file. Use this ONLY when an authenticated user asks to download the categories PDF file')]
    public function downloadCategoriesFile()
    {
        // Check authentication before allowing download
        $this->initializeAuth();
        if (!$this->isAuthenticated) {
            return [
                'error' => 'Authentication required. You must be logged in to download files. Please log in first.',
                'success' => false
            ];
        }
        
        // Return the download URL instead of trying to download directly
        // The frontend will handle the actual download
        $url = route('download.categories');
        return [
            'success' => true,
            'message' => 'Categories file download ready',
            'download_url' => $url,
            'filename' => 'categories.pdf'
        ];
    }

    #[Tool('download the products file. Use this ONLY when an authenticated user asks to download the products PDF file')]
    public function downloadProductsFile()
    {
        // Check authentication before allowing download
        $this->initializeAuth();
        if (!$this->isAuthenticated) {
            return [
                'error' => 'Authentication required. You must be logged in to download files. Please log in first.',
                'success' => false
            ];
        }
        
        // Return the download URL instead of trying to download directly
        // The frontend will handle the actual download
        $url = route('download.products');
        return [
            'success' => true,
            'message' => 'Products file download ready',
            'download_url' => $url,
            'filename' => 'products.pdf'
        ];
    }
}
