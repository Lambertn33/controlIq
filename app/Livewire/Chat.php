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

        $agent = $this->getSupportAgent();
        $chatHistory = $agent->chatHistory();
        foreach ($chatHistory->getMessages() as $message) {
            $normalized = $this->normalizeAgentMessage($message);
            if ($normalized && $normalized !== null) {
                $this->messages[] = $normalized;
            }
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
        
        $this->dispatch('scroll-to-bottom');
    }
    
    public function updatedMessages()
    {
        $this->dispatch('scroll-to-bottom');
    }

    public function clearMessages()
    {
        try {
            $agent = $this->getSupportAgent();
            $chatHistory = $agent->chatHistory();
            $chatHistory->clear();
            $this->messages = [];
            $this->dispatch('scroll-to-bottom');
        } catch (\Throwable $th) {
            \Log::error('Error clearing chat history: ' . $th->getMessage());
            // Still clear the component messages even if history clear fails
            $this->messages = [];
        }
    }

    protected function normalizeAgentMessage($message): ?array
    {
        $role = $message['role'] ?? null;

        // 1) Skip system messages
        if ($role === 'system') {
            return null;
        }

        $content = $message['content'] ?? null;
        $text = null;

        if (is_array($content) && isset($content[0]) && is_array($content[0])) {
            foreach ($content as $block) {
                if (($block['type'] ?? null) === 'text') {
                    $text = $block['text'] ?? null;
                    break;
                }
            }
        }

        // 3) ASSISTANT shape: content is a single object-like array: ['type'=>'text','text'=>'...']
        if ($text === null && is_array($content) && isset($content['type'])) {
            if (($content['type'] ?? null) === 'text') {
                $text = $content['text'] ?? null;
            }
        }

        // 4) Fallback: if content is plain string (just in case)
        if ($text === null && is_string($content)) {
            $text = $content;
        }

        $text = is_string($text) ? trim($text) : null;

        if ($text === null || $text === '') {
            return null;
        }

        return [
            'role' => $role, // user | assistant
            'message' => $text,
            'timestamp' => isset($message['message_created'])
                ? \Carbon\Carbon::parse($message['message_created'])->toDateTimeString()
                : now()->toDateTimeString(),
        ];
    }
}
