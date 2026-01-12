<?php

namespace App\Livewire;

use Livewire\Component;

use App\AiAgents\SupportAgent;

class Chat extends Component
{
    public $messages = [];

    public $enteredMessage = '';

    public $isSending = false;

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
        if (auth()->check()) {
            $this->addMessage('assistant', 'Hello, ' . auth()->user()->name . ', how can I help you today?');
        } else {
            $this->addMessage('assistant', 'Hello, we can\'t help you if you\'re not authenticated. Please login to use the system.');
        }
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
    }
}
