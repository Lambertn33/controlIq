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
            
            // Check for download URLs in tool responses and extract them
            $downloadUrl = $this->extractDownloadUrlFromHistory();
            
            // Add message with download URL if found
            if ($downloadUrl) {
                $processed = $this->processMessageForDownloads($responseText);
                $this->messages[] = [
                    'role' => 'assistant',
                    'message' => $processed['text'] ?: $responseText,
                    'download_url' => $downloadUrl,
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->dispatch('scroll-to-bottom');
            } else {
                $this->addMessage('assistant', $responseText);
            }
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
        // Extract download URLs from message and convert to buttons
        $processedMessage = $this->processMessageForDownloads($message);
        
        $this->messages[] = [
            'role' => $role,
            'message' => $processedMessage['text'],
            'download_url' => $processedMessage['download_url'] ?? null,
            'timestamp' => now()->toDateTimeString(),
        ];
        
        $this->dispatch('scroll-to-bottom');
    }
    
    protected function processMessageForDownloads(string $message): array
    {
        $downloadUrl = null;
        $text = $message;
        
        // Check for download URLs in the message
        if (preg_match('/download\/(categories|products)/i', $message, $matches)) {
            $type = strtolower($matches[1]);
            $downloadUrl = route('download.' . $type);
            
            // Remove the URL from the text and replace with a placeholder
            $text = preg_replace('/\(?https?:\/\/[^\)\s]+\)?/i', '', $text);
            $text = preg_replace('/\[Download[^\]]+\]/i', '', $text);
            $text = trim($text);
        } elseif (preg_match('/http:\/\/localhost:8000\/download\/(categories|products)/i', $message, $matches)) {
            $type = strtolower($matches[1]);
            $downloadUrl = route('download.' . $type);
            
            // Remove the URL from the text
            $text = preg_replace('/\(?http:\/\/localhost:8000\/download\/[^\)\s]+\)?/i', '', $text);
            $text = preg_replace('/\[Download[^\]]+\]/i', '', $text);
            $text = trim($text);
        }
        
        return [
            'text' => $text,
            'download_url' => $downloadUrl,
        ];
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

    protected function extractDownloadUrlFromHistory(): ?string
    {
        try {
            $agent = $this->getSupportAgent();
            $chatHistory = $agent->chatHistory();
            $messages = $chatHistory->getMessages();
            
            // Check the last few messages for tool calls with download URLs
            $recentMessages = array_slice($messages, -5);
            
            foreach (array_reverse($recentMessages) as $message) {
                $msg = is_object($message) ? (array) $message : $message;
                
                // Check if this is a tool response with download_url
                if (isset($msg['role']) && $msg['role'] === 'tool') {
                    $content = $msg['content'] ?? null;
                    
                    if (is_array($content)) {
                        // Check if content has download_url directly
                        if (isset($content['download_url'])) {
                            return $content['download_url'];
                        }
                        
                        // Check nested content structure
                        $contentStr = json_encode($content);
                        if (preg_match('/"download_url"\s*:\s*"([^"]+)"/', $contentStr, $matches)) {
                            return $matches[1];
                        }
                    } elseif (is_string($content)) {
                        // Try to parse as JSON
                        $decoded = json_decode($content, true);
                        if (is_array($decoded) && isset($decoded['download_url'])) {
                            return $decoded['download_url'];
                        }
                        
                        // Try regex extraction
                        if (preg_match('/"download_url"\s*:\s*"([^"]+)"/', $content, $matches)) {
                            return $matches[1];
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            \Log::warning('Error extracting download URL: ' . $th->getMessage());
        }
        
        return null;
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

        // Process message for download URLs
        $processed = $this->processMessageForDownloads($text);

        return [
            'role' => $role, // user | assistant
            'message' => $processed['text'],
            'download_url' => $processed['download_url'] ?? null,
            'timestamp' => isset($message['message_created'])
                ? \Carbon\Carbon::parse($message['message_created'])->toDateTimeString()
                : now()->toDateTimeString(),
        ];
    }
}
