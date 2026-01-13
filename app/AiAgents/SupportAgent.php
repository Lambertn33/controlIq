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

    #[Tool('get the categories in case the user wants to view them')]
    public function viewCategories()
    {
        return ProductsServices::getAllCategories();
    }
    
}
