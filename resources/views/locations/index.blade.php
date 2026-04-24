<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Locations
            </h2>
            <a href="{{ route('locations.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                + Nouvelle location
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full text-left text-gray-900 dark:text-gray-100">
                        <thead class="border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="pb-3 pr-6">Nom</th>
                                <th class="pb-3 pr-6">Ville / Pays</th>
                                <th class="pb-3 pr-6">Film</th>
                                <th class="pb-3 pr-6">Ajouté par</th>
                                <th class="pb-3 pr-6">Upvotes</th>
                                <th class="pb-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($locations as $location)
                                <tr
                                    class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-3 pr-6 font-medium">{{ $location->name }}</td>
                                    <td class="py-3 pr-6 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $location->city }}, {{ $location->country }}
                                    </td>
                                    <td class="py-3 pr-6">
                                        <a href="{{ route('films.show', $location->film) }}"
                                            class="text-indigo-600 hover:underline text-sm">
                                            {{ $location->film->title }}
                                        </a>
                                    </td>
                                    <td class="py-3 pr-6 text-sm">{{ $location->user->name }}</td>
                                    <td class="py-3 pr-6">
                                        <span
                                            class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">
                                            {{ $location->upvotes_count }}
                                        </span>
                                    </td>
                                    <td class="py-3 flex items-center gap-2">
                                        @if (auth()->user()->is_admin || auth()->id() === $location->user_id)
                                            <a href="{{ route('locations.edit', $location) }}"
                                                class="text-sm text-yellow-600 hover:underline">
                                                Modifier
                                            </a>
                                            <a href="{{ route('locations.show', $location) }}"
                                                class="text-sm text-blue-600 hover:underline">
                                                Voir
                                            </a>
                                            <form action="{{ route('locations.destroy', $location) }}" method="POST"
                                                onsubmit="return confirm('Supprimer cette location ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:underline">
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-gray-400">Aucune location.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $locations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
