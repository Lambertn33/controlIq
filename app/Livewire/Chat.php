<?php

namespace App\Livewire;

use Livewire\Component;

class Chat extends Component
{
    public $messages = [];

    public $enteredMessage = '';

    public $isSending = false;

    public function sendMessage()
    {
        $this->isSending = true;
        $this->messages[] = $this->enteredMessage;
        $this->enteredMessage = '';
        $this->isSending = false;
    }

    public function mount()
    {
        $this->messages[] = 'Hello, how can I help you today?';
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
