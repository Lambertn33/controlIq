<nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}"
                    class="text-xl font-semibold text-gray-900 hover:text-indigo-600 transition-colors">
                    ControliQ
                </a>
            </div>
            <div class="flex items-center space-x-4">
                @auth
                    <span class="text-gray-700">Welcome, {{ Auth::user()->name }}!</span>
                    @if (Auth::user()->role === \App\Models\User::ADMIN)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ Auth::user()->role }}
                        </span>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 transition-colors">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
