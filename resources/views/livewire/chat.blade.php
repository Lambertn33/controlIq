<div>
    <div class="flex flex-col gap-4">
        @foreach ($messages as $message)
            <div class="bg-white p-4 rounded-lg shadow">
                {{ $message }}
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
