@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-indigo-50 to-purple-50">
        <!-- Navigation -->
        @include('components.nav')

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Welcome Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Welcome to ControliQ</h1>
                                <p class="text-gray-600 mt-1">Your intelligent assistant is ready to help</p>
                            </div>
                        </div>

                        @auth
                            <div
                                class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4 mb-6 border border-indigo-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">You're logged in as {{ Auth::user()->name }}</p>
                                        <p class="text-sm text-gray-600">Ready to get started!</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-blue-50 rounded-xl p-4 mb-6 border border-blue-100">
                                <p class="text-gray-700">
                                    <a href="{{ route('login') }}"
                                        class="text-indigo-600 hover:text-indigo-700 font-semibold">Log in</a> to start chatting
                                    with our AI assistant.
                                </p>
                            </div>
                        @endauth

                        <div class="prose prose-sm max-w-none">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">What can I help you with?</h3>
                            <ul class="text-gray-600 space-y-2">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-indigo-500 mt-0.5 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Browse and search products
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-indigo-500 mt-0.5 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    View product categories
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-indigo-500 mt-0.5 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Get answers to your questions
                                </li>
                                @auth
                                    @if (Auth::user()->role === \App\Models\User::ADMIN)
                                        <li class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-purple-500 mt-0.5 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-purple-600 font-semibold">Create and manage categories
                                                (Admin)</span>
                                        </li>
                                    @endif
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Chat Section -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden sticky top-4">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                AI Assistant
                            </h2>
                        </div>
                        <div class="p-6">
                            <livewire:chat />
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
