<div class="flex flex-col h-[600px]" x-data="{
    scrollToBottom() {
            this.$nextTick(() => {
                const container = document.getElementById('chat-messages');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },
        get isButtonDisabled() {
            return $wire.isSending || !$wire.enteredMessage || $wire.enteredMessage.trim() === '';
        }
}" x-init="scrollToBottom()"
    @scroll-to-bottom.window="scrollToBottom()" x-effect="$watch('$wire.messages.length', () => scrollToBottom())">
    <!-- Messages Container Header -->
    <div class="flex items-center justify-between mb-2 px-2">
        <h3 class="text-sm font-semibold text-gray-700">Chat History</h3>
        @if (count($messages) > 0)
            <button type="button" wire:click="clearMessages"
                class="flex items-center cursor-pointer gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
                Clear chat
            </button>
        @endif
    </div>

    <!-- Messages Container -->
    <div class="flex-1 flex flex-col gap-3 overflow-y-auto p-2 mb-4 bg-gray-50 rounded-lg" id="chat-messages">
        @forelse($messages as $message)
            <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}"
                wire:key="message-{{ $loop->index }}">
                <div
                    class="flex items-start gap-2 max-w-[85%] {{ $message['role'] === 'user' ? 'flex-row-reverse' : 'flex-row' }}">
                    @if ($message['role'] === 'assistant')
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                    @endif
                    <div class="flex flex-col {{ $message['role'] === 'user' ? 'items-end' : 'items-start' }}">
                        <div
                            class="rounded-2xl px-4 py-2.5 {{ $message['role'] === 'user'
                                ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-tr-sm'
                                : 'bg-white text-gray-900 border border-gray-200 rounded-tl-sm shadow-sm' }}">
                            <p class="whitespace-pre-wrap text-sm leading-relaxed">{{ $message['message'] }}</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 px-1">
                            {{ \Carbon\Carbon::parse($message['timestamp'])->format('H:i') }}
                        </p>
                    </div>
                    @if ($message['role'] === 'user')
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="flex items-center justify-center h-full text-center">
                <div class="text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    <p class="text-sm">Start a conversation...</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Input Form -->
    <form wire:submit="sendMessage" class="flex gap-2">
        <div class="flex-1 relative">
            <input type="text" wire:model="enteredMessage" placeholder="Type your message..."
                class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white shadow-sm transition-all"
                @keydown.enter.prevent="if (!$wire.isSending && $wire.enteredMessage.trim()) { $wire.sendMessage(); }" />
            <div class="absolute right-3 top-1/2 -translate-y-1/2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
        <button type="submit" x-bind:disabled="isButtonDisabled" wire:loading.attr="disabled"
            wire:loading.class="opacity-50 cursor-not-allowed"
            class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:from-indigo-600 disabled:hover:to-purple-600 flex items-center gap-2 font-medium">
            <span wire:loading.remove wire:target="sendMessage">{{ $isSending ? 'Sending...' : 'Send' }}</span>
            <span wire:loading wire:target="sendMessage" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Sending...
            </span>
        </button>
    </form>
</div>
