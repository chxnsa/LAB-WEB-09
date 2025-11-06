@extends('layouts.app')

@section('title', 'Fish Database')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Fish Database</h2>
    <a href="{{ route('fishes.create') }}" class="btn btn-primary">Add New Fish</a>
</div>

<div class="filter-section">
    <form method="GET" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Search Fish</label>
            <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
        </div>
        
        <div class="col-md-3">
            <label class="form-label">Filter by Rarity</label>
            <select name="rarity" class="form-select">
                <option value="">All Rarities</option>
                @foreach($rarities as $rarity)
                    <option value="{{ $rarity }}" {{ request('rarity') == $rarity ? 'selected' : '' }}>
                        {{ $rarity }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-2">
            <label class="form-label">Sort By</label>
            <select name="sort_by" class="form-select">
                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                <option value="sell_price_per_kg" {{ request('sort_by') == 'sell_price_per_kg' ? 'selected' : '' }}>Price</option>
                <option value="catch_probability" {{ request('sort_by') == 'catch_probability' ? 'selected' : '' }}>Probability</option>
            </select>
        </div>
        
        <div class="col-md-2">
            <label class="form-label">Order</label>
            <select name="sort_order" class="form-select">
                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
            </select>
        </div>
        
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Rarity</th>
                        <th>Weight Range</th>
                        <th>Price/kg</th>
                        <th>Catch Rate</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fishes as $index => $fish)
                        <tr>
                            <td><strong>{{ $fishes->firstItem() + $index }}</strong></td>
                            <td>{{ $fish->name }}</td>
                            <td>
                                <span class="badge bg-{{ $fish->rarity_color }}">
                                    {{ $fish->rarity }}
                                </span>
                            </td>
                            <td>{{ $fish->formatted_weight_range }}</td>
                            <td>{{ $fish->formatted_price }}</td>
                            <td>{{ $fish->catch_probability }}%</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('fishes.show', $fish) }}" class="btn btn-info">View</a>
                                    <a href="{{ route('fishes.edit', $fish) }}" class="btn btn-warning">Edit</a>
                                    <form method="POST" action="{{ route('fishes.destroy', $fish) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this fish?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted mb-0">No fish data found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center">
    {{ $fishes->links() }}
</div>
@endsection