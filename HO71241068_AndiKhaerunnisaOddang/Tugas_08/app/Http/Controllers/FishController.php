<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use Illuminate\Http\Request;

class FishController extends Controller
{
    public function index(Request $request)
    {
        $query = Fish::query();

        // Filter berdasarkan rarity
        if ($request->has('rarity') && $request->rarity != '') {
            $query->where('rarity', $request->rarity);
        }

        // Search berdasarkan nama
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['name', 'sell_price_per_kg', 'catch_probability', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $fishes = $query->paginate(10)->withQueryString();

        $rarities = ['Common', 'Uncommon', 'Rare', 'Epic', 'Legendary', 'Mythic', 'Secret'];

        return view('fishes.index', compact('fishes', 'rarities'));
    }

    public function create()
    {
        $rarities = ['Common', 'Uncommon', 'Rare', 'Epic', 'Legendary', 'Mythic', 'Secret'];
        return view('fishes.create', compact('rarities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'rarity' => 'required|in:Common,Uncommon,Rare,Epic,Legendary,Mythic,Secret',
            'base_weight_min' => 'required|numeric|min:0',
            'base_weight_max' => 'required|numeric|gt:base_weight_min',
            'sell_price_per_kg' => 'required|integer|min:0',
            'catch_probability' => 'required|numeric|min:0.01|max:100',
            'description' => 'nullable|string'
        ], [
            'base_weight_max.gt' => 'Berat maksimum harus lebih besar dari berat minimum.',
            'catch_probability.min' => 'Peluang tangkap minimal 0.01%.',
            'catch_probability.max' => 'Peluang tangkap maksimal 100%.'
        ]);

        Fish::create($request->all());

        return redirect()->route('fishes.index')
            ->with('success', 'Fish data added successfully!');
    }

    public function show(Fish $fish)
    {
        return view('fishes.show', compact('fish'));
    }

    public function edit(Fish $fish)
    {
        $rarities = ['Common', 'Uncommon', 'Rare', 'Epic', 'Legendary', 'Mythic', 'Secret'];
        return view('fishes.edit', compact('fish', 'rarities'));
    }

    public function update(Request $request, Fish $fish)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'rarity' => 'required|in:Common,Uncommon,Rare,Epic,Legendary,Mythic,Secret',
            'base_weight_min' => 'required|numeric|min:0',
            'base_weight_max' => 'required|numeric|gt:base_weight_min',
            'sell_price_per_kg' => 'required|integer|min:0',
            'catch_probability' => 'required|numeric|min:0.01|max:100',
            'description' => 'nullable|string'
        ], [
            'base_weight_max.gt' => 'Berat maksimum harus lebih besar dari berat minimum.',
            'catch_probability.min' => 'Peluang tangkap minimal 0.01%.',
            'catch_probability.max' => 'Peluang tangkap maksimal 100%.'
        ]);

        $fish->update($request->all());

        return redirect()->route('fishes.index')
            ->with('success', 'Fish data updated successfully!');
    }

    public function destroy(Fish $fish)
    {
        $fish->delete();

        return redirect()->route('fishes.index')
            ->with('success', 'Fish data deleted successfully!');
    }
}