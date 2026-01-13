<?php

namespace App\Livewire;

use Livewire\Component;

use App\AiAgents\SupportAgent;

use App\Services\AuthServices;

class Chat extends Component
{
    public $messages = [];

    public $enteredMessage = '';

    public $isSending = false;

    public $isAuthenticated = false;

    protected function getSupportAgent()
    {
        return new SupportAgent();
    }

    public function sendMessage()
    {
        $this->validate([
            'enteredMessage' => 'required|string|max:255',
        ]);
       
        $userMessage = trim($this->enteredMessage);
        $this->addMessage('user', $userMessage);
        $this->isSending = true;

        try {
            $agent = $this->getSupportAgent();
            $response = $agent->message($userMessage)->respond();
            $responseText = is_string($response) ? $response : (string) $response;
            $this->addMessage('assistant', $responseText);
        } catch (\Throwable $th) {
            $this->addMessage('assistant', 'Sorry, I encountered an error. Please try again.');
            \Log::error('Error in sendMessage: ' . $th->getMessage());
        } finally {
            $this->isSending = false;
            $this->enteredMessage = '';
        }

    }

    public function mount()
    {
        $this->messages = [];
        $this->enteredMessage = '';
        $this->isSending = false;
        $this->isAuthenticated = AuthServices::checkIfUserIsAuthenticated()['isAuthenticated'];
    }

    public function render()
    {
        return view('livewire.chat');
    }

    protected function addMessage(string $role, string $message)
    {
        $this->messages[] = [
            'role' => $role,
            'message' => $message,
            'timestamp' => now()->toDateTimeString(),
        ];
        
        $this->dispatch('scroll-to-bottom');
    }
    
    public function updatedMessages()
    {
        $this->dispatch('scroll-to-bottom');
    }
}
