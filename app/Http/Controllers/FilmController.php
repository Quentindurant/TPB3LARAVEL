<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FilmController extends Controller
{
    public function index(): View
    {
        return view('films.index', [
            'films' => Film::query()
                ->withCount('locations')
                ->orderBy('upvotes_count', 'asc')
                ->paginate(10),
        ]);
    }

    public function show(Film $film): View
    {
        $film->load([
            'locations' => fn ($query) => $query->with('user')->orderBy('name'),
        ]);

        return view('films.show', [
            'film' => $film,
        ]);
    }

    public function create(): View
    {
        return view('films.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $film = Film::create($this->validatedData($request));

        return redirect()
            ->route('films.index')
            ->with('success', 'Film ajoute.');
    }

    public function edit(Film $film): View
    {
        return view('films.edit', [
            'film' => $film,
        ]);
    }

    public function update(Request $request, Film $film): RedirectResponse
    {
        $film->update($this->validatedData($request, $film));

        return redirect()
            ->route('films.index')
            ->with('success', 'Film modifie.');
    }

    public function destroy(Film $film): RedirectResponse
    {
        $film->delete();

        return redirect()
            ->route('films.index')
            ->with('success', 'Film supprime.');
    }

    private function validatedData(Request $request, ?Film $film = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'release_year' => 'required|integer',
            'synopsis' => 'nullable|string',
        ]);
    }
}
