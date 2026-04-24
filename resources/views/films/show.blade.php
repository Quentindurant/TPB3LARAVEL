<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('films.index') }}" class="text-indigo-600 hover:underline text-sm">&larr; Films</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $film->title }} ({{ $film->release_year }})
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Synopsis --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Synopsis</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ $film->synopsis ?? 'Aucun synopsis.' }}</p>
            </div>

            {{-- Locations --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-4">
                    Emplacements ({{ $film->locations->count() }})
                </h3>

                @forelse ($film->locations as $location)
                    <div class="border border-gray-100 dark:border-gray-700 rounded-lg p-4 mb-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $location->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $location->city }}, {{ $location->country }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $location->description }}</p>
                            </div>
                            <div class="text-right text-sm text-gray-400">
                                <p>Par {{ $location->user->name }}</p>
                                <p class="mt-1">
                                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">
                                        {{ $location->upvotes_count }} upvotes
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400">Aucun emplacement pour ce film.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
