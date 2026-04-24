<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $location->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <p><strong>Film :</strong> {{ $location->film->title }}</p>
                    <p><strong>Lieu :</strong> {{ $location->name }}</p>
                    <p><strong>Ville :</strong> {{ $location->city }}</p>
                    <p><strong>Pays :</strong> {{ $location->country }}</p>
                    <p><strong>Description :</strong> {{ $location->description }}</p>
                    <p><strong>Ajouté par :</strong> {{ $location->user->name }}</p>
                    <p><strong>Nombre de votes :</strong> {{ $location->upvotes_count }}</p>
                    <form method="POST" action="{{ route('locations.upvote', $location) }}">
                        @csrf
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                            Voter
                        </button>
                    </form>
                    @if (session('success'))
                        <p class="text-green-600">{{ session('success') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
