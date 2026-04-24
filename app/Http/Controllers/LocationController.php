<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        return view('locations.index', [
            'locations' => Location::query()
                ->with('film', 'user')
                ->latest()
                ->paginate(10),

        ]);
    }

    public function show(Location $location): View
    {
        $location->load([
            'film' => fn ($query) => $query->with('user'),
        ]);

        return view('locations.show', [
            'location' => $location,
        ]);
    }

    public function create(): View
    {
        return view('locations.create', [
            'films' => Film::query()->orderBy('title')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $request->session()->put('pending_location', $data);

        return redirect()
            ->route('locations.paiement');
    }

    public function edit(Location $location): View
    {
        return view('locations.edit', [
            'location' => $location,
            'films' => Film::query()->orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, Location $location): RedirectResponse
    {
        $location->update($this->validatedData($request, $location));

        return redirect()
            ->route('locations.index')
            ->with('success', 'Film modifie.');
    }

    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();

        return redirect()
            ->route('locations.index')
            ->with('success', 'Film supprime.');
    }

    private function validatedData(Request $request, ?Location $location = null): array
    {
        return $request->validate([
            'film_id' => 'required|exists:films,id',
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    }
}
