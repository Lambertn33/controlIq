@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        @include('components.nav')

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="border-4 border-dashed border-gray-200 rounded-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome</h2>

                    @auth
                        <p class="text-gray-600 mb-6">You are successfully logged in!</p>
                    @endauth
                    <div class="mt-6 space-y-4">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="font-semibold text-gray-900 mb-3">Start Chatting</h3>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
