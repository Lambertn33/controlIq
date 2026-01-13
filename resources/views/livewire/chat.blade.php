<div class="flex flex-col gap-4" x-data="{
    scrollToBottom() {
        this.$nextTick(() => {
            const container = document.getElementById('chat-messages');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    }
}" x-init="scrollToBottom()" @scroll-to-bottom.window="scrollToBottom()"
    x-effect="$watch('$wire.messages.length', () => scrollToBottom())">
    <div class="flex flex-col gap-4 max-h-[400px] overflow-y-auto p-2" id="chat-messages">
        @foreach ($messages as $message)
            <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}"
                wire:key="message-{{ $loop->index }}">
                <div
                    class="max-w-[80%] rounded-lg px-4 py-2 {{ $message['role'] === 'user' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }}">
                    <p class="whitespace-pre-wrap">{{ $message['message'] }}</p>
                    <p class="text-xs mt-1 opacity-70">
                        {{ $message['role'] === 'user' ? 'You' : 'Assistant' }} •
                        {{ \Carbon\Carbon::parse($message['timestamp'])->format('H:i') }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <form wire:submit="sendMessage" class="flex gap-4 w-full">
        <input type="text" wire:model="enteredMessage" placeholder="Enter your message"
            class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
        <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">{{ $isSending ? 'Sending...' : 'Send' }}</button>
    </form>
</div>
