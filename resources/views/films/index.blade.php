<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Films
            </h2>
            @if (auth()->user()->is_admin)
                <a href="{{ route('films.create') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                    + Nouveau film
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full text-left text-gray-900 dark:text-gray-100">
                        <thead class="border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="pb-3 pr-6">Titree</th>
                                <th class="pb-3 pr-6">Année</th>
                                <th class="pb-3 pr-6">Synopsis</th>
                                <th class="pb-3 pr-6">Nombre de locations</th>
                                <th class="pb-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($films as $film)
                                <tr
                                    class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-3 pr-6 font-medium">
                                        <a href="{{ route('films.show', $film) }}"
                                            class="text-indigo-600 hover:underline">
                                            {{ $film->title }}
                                        </a>
                                    </td>
                                    <td class="py-3 pr-6">{{ $film->release_year }}</td>
                                    <td class="py-3 pr-6 text-sm text-gray-500 dark:text-gray-400">
                                        {{ Str::limit($film->synopsis, 80) }}
                                    </td>
                                    <td class="py-3 pr-6">
                                        <span
                                            class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-2 py-1 rounded-full">
                                            {{ $film->locations_count }}
                                        </span>
                                    </td>
                                    <td class="py-3 flex items-center gap-2">
                                        @if (auth()->user()->is_admin)
                                            <a href="{{ route('films.edit', $film) }}"
                                                class="text-sm text-yellow-600 hover:underline">
                                                Modifier
                                            </a>
                                            <form action="{{ route('films.destroy', $film) }}" method="POST"
                                                onsubmit="return confirm('Supprimer ce film ?')">
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
                                    <td colspan="5" class="py-6 text-center text-gray-400">Aucun film.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $films->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
