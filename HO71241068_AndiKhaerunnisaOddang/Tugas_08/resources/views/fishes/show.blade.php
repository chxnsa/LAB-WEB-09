@extends('layouts.app')

@section('title', 'Fish Details')

@section('content')
<div class="mb-4">
    <a href="{{ route('fishes.index') }}" class="btn btn-secondary">
        Back to Database
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="mb-0">Fish Information</h3>
    </div>
    <div class="card-body p-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="text-muted small">ID</label>
                    <p class="h5 mb-0">#{{ $fish->id }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Fish Name</label>
                    <p class="h4 mb-0">{{ $fish->name }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Rarity</label>
                    <div>
                        <span class="badge bg-{{ $fish->rarity_color }} fs-6">{{ $fish->rarity }}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Weight Range</label>
                    <p class="h5 mb-0">{{ $fish->formatted_weight_range }}</p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="text-muted small">Sell Price per kg</label>
                    <p class="h5 mb-0">{{ $fish->formatted_price }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Catch Probability</label>
                    <p class="h5 mb-0">{{ $fish->catch_probability }}%</p>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Created At</label>
                    <p class="mb-0">{{ $fish->created_at->format('d M Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Updated At</label>
                    <p class="mb-0">{{ $fish->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
        
        <div class="mb-4">
            <label class="text-muted small">Description</label>
            <p class="mb-0">{{ $fish->description ?? 'No description available.' }}</p>
        </div>
        
        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('fishes.edit', $fish) }}" class="btn btn-warning px-4">Edit Fish</a>
            <form method="POST" action="{{ route('fishes.destroy', $fish) }}" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger px-4" 
                        onclick="return confirm('Are you sure you want to delete this fish?')">
                    Delete Fish
                </button>
            </form>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h4 class="mb-0">Statistics</h4>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="p-3">
                    <p class="text-muted small mb-1">Average Weight</p>
                    <h4 class="mb-0">{{ number_format(($fish->base_weight_min + $fish->base_weight_max) / 2, 2) }} kg</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3">
                    <p class="text-muted small mb-1">Max Potential Value</p>
                    <h4 class="mb-0">{{ number_format($fish->base_weight_max * $fish->sell_price_per_kg, 0, ',', '.') }} Coins</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3">
                    <p class="text-muted small mb-1">Min Potential Value</p>
                    <h4 class="mb-0">{{ number_format($fish->base_weight_min * $fish->sell_price_per_kg, 0, ',', '.') }} Coins</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection