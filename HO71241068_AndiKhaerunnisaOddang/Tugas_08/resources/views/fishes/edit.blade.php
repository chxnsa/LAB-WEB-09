@extends('layouts.app')

@section('title', 'Edit Fish')

@section('content')
<div class="mb-4">
    <h2>Edit Fish</h2>
    <p class="text-muted">Update the fish information</p>
</div>

<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('fishes.update', $fish) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fish Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                        name="name" value="{{ old('name', $fish->name) }}" required autofocus 
                        placeholder="e.g., Great White Shark">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Rarity <span class="text-danger">*</span></label>
                    <select class="form-select @error('rarity') is-invalid @enderror" name="rarity" required>
                        <option value="">Select Rarity</option>
                        @foreach($rarities as $rarity)
                            <option value="{{ $rarity }}" {{ old('rarity', $fish->rarity) == $rarity ? 'selected' : '' }}>
                                {{ $rarity }}
                            </option>
                        @endforeach
                    </select>
                    @error('rarity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Minimum Weight (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('base_weight_min') is-invalid @enderror" 
                        name="base_weight_min" value="{{ old('base_weight_min', $fish->base_weight_min) }}" required 
                        placeholder="e.g., 0.50">
                    @error('base_weight_min')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Maximum Weight (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('base_weight_max') is-invalid @enderror" 
                        name="base_weight_max" value="{{ old('base_weight_max', $fish->base_weight_max) }}" required 
                        placeholder="e.g., 2.50">
                    @error('base_weight_max')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sell Price per kg (Coins) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('sell_price_per_kg') is-invalid @enderror" 
                        name="sell_price_per_kg" value="{{ old('sell_price_per_kg', $fish->sell_price_per_kg) }}" required 
                        placeholder="e.g., 1000">
                    @error('sell_price_per_kg')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Catch Probability (%) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('catch_probability') is-invalid @enderror" 
                        name="catch_probability" value="{{ old('catch_probability', $fish->catch_probability) }}" required 
                        placeholder="e.g., 15.50">
                    <small class="text-muted">Range: 0.01% - 100%</small>
                    @error('catch_probability')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                        name="description" rows="4" 
                        placeholder="Enter fish description (optional)...">{{ old('description', $fish->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">Update Fish</button>
                <a href="{{ route('fishes.index') }}" class="btn btn-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection