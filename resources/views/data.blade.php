@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        @include('components.nav')

        <!-- Main Content -->
        <main class="py-6 sm:px-6 lg:px-8">
            <div class="flex flex-row gap-4">
                <div class="w-full">
                    <livewire:categories />
                    <livewire:products />
                </div>
            </div>
        </main>
    </div>
@endsection
