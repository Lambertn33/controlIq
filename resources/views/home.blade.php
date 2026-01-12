@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900">{{ config('app.name', 'Laravel') }}</h1>
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
                                <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="border-4 border-dashed border-gray-200 rounded-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome</h2>

                    @auth
                        <p class="text-gray-600 mb-6">You are successfully logged in!</p>

                        <div class="mt-6 space-y-4">
                            <div class="bg-white p-4 rounded-lg shadow">
                                <h3 class="font-semibold text-gray-900 mb-3">User Information</h3>
                                <ul class="space-y-1 text-sm text-gray-600">
                                    <li><strong>Name:</strong> {{ Auth::user()->name }}</li>
                                    <li><strong>Email:</strong> {{ Auth::user()->email }}</li>
                                    <li><strong>Role:</strong> {{ Auth::user()->role }}</li>
                                </ul>
                            </div>

                            <!-- Authenticated User Actions -->
                            <div class="bg-white p-6 rounded-lg shadow">
                                <h3 class="font-semibold text-gray-900 mb-4">Available Actions</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <h4 class="font-medium text-gray-900 mb-2">View Profile</h4>
                                        <p class="text-sm text-gray-600 mb-3">View and edit your profile information</p>
                                        <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            Go to Profile →
                                        </button>
                                    </div>

                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <h4 class="font-medium text-gray-900 mb-2">Manage Products</h4>
                                        <p class="text-sm text-gray-600 mb-3">Browse and manage products</p>
                                        <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            View Products →
                                        </button>
                                    </div>

                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <h4 class="font-medium text-gray-900 mb-2">Settings</h4>
                                        <p class="text-sm text-gray-600 mb-3">Manage your account settings</p>
                                        <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            Open Settings →
                                        </button>
                                    </div>

                                    @if (Auth::user()->role === \App\Models\User::ADMIN)
                                        <div
                                            class="border border-purple-200 rounded-lg p-4 hover:shadow-md transition-shadow bg-purple-50">
                                            <h4 class="font-medium text-gray-900 mb-2">Admin Panel</h4>
                                            <p class="text-sm text-gray-600 mb-3">Access admin-only features</p>
                                            <button class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                                Admin Dashboard →
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-600 mb-6">Welcome to our application! Please sign in to access all features.</p>

                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="font-semibold text-gray-900 mb-4">Get Started</h3>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-700">Sign in to access all features</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-700">Manage your products and categories</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-700">Secure and easy to use</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('login') }}"
                                    class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 font-medium">
                                    Sign In
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </main>
    </div>
@endsection
