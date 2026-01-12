<?php

namespace App\AiAgents;

use LarAgent\Agent;

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

    public function instructions()
    {
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
    
}
