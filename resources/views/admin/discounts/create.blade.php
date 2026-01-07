@extends('layouts.app')

@section('title', 'Create Discount')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-plus-circle"></i> Create New Discount</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Discounts
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validation Errors!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.discounts.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Discount Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" placeholder="e.g., Year End Sale, Valentine Special" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Describe this discount...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" name="type" required onchange="updateValueLabel()">
                                    <option value="">-- Select Type --</option>
                                    <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount (Rp)</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="value" class="form-label">
                                    <span id="valueLabel">Discount Value</span> <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.01" class="form-control @error('value') is-invalid @enderror" 
                                       id="value" name="value" placeholder="Enter discount value" 
                                       value="{{ old('value') }}" required>
                                <small class="form-text text-muted" id="valueHint">
                                    For percentage: enter 0-100. For fixed: enter amount in Rupiah
                                </small>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date (Optional)</label>
                                <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" value="{{ old('end_date') }}">
                                <small class="form-text text-muted">Leave empty for no expiry date</small>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usage_limit" class="form-label">Usage Limit (Optional)</label>
                                <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                                       id="usage_limit" name="usage_limit" placeholder="e.g., 100" 
                                       value="{{ old('usage_limit') }}">
                                <small class="form-text text-muted">Leave empty for unlimited usage</small>
                                @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                                <small class="form-text text-muted d-block mt-2">Uncheck to disable this discount</small>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="packages" class="form-label">Apply to Packages (Optional)</label>
                            <select class="form-select @error('packages') is-invalid @enderror" 
                                    id="packages" name="packages[]" multiple>
                                @forelse ($packages as $package)
                                    <option value="{{ $package->id }}" 
                                            {{ in_array($package->id, old('packages', [])) ? 'selected' : '' }}>
                                        {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </option>
                                @empty
                                    <option disabled>No packages available</option>
                                @endforelse
                            </select>
                            <small class="form-text text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> Leave empty to apply discount to all packages
                            </small>
                            @error('packages')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Discount
                            </button>
                            <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Tips</h5>
                </div>
                <div class="card-body">
                    <h6>Flash Sale Example</h6>
                    <ul class="small">
                        <li>Name: "Year End Sale"</li>
                        <li>Type: Percentage</li>
                        <li>Value: 30</li>
                        <li>Start: Today</li>
                        <li>End: End of year</li>
                    </ul>

                    <hr>

                    <h6>Early Bird Special</h6>
                    <ul class="small">
                        <li>Name: "Early Booking Discount"</li>
                        <li>Type: Fixed</li>
                        <li>Value: 500000 (Rp)</li>
                        <li>No expiry date</li>
                        <li>Select specific packages</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: none;
        border-radius: 8px;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .text-danger {
        color: #dc3545 !important;
    }
</style>

<script>
function updateValueLabel() {
    const type = document.getElementById('type').value;
    const label = document.getElementById('valueLabel');
    const hint = document.getElementById('valueHint');
    
    if (type === 'percentage') {
        label.textContent = 'Discount Percentage (%)';
        hint.textContent = 'Enter 0-100 for percentage discount';
    } else if (type === 'fixed') {
        label.textContent = 'Discount Amount (Rp)';
        hint.textContent = 'Enter fixed amount in Rupiah';
    }
}

// Update on page load
document.addEventListener('DOMContentLoaded', updateValueLabel);
</script>
@endsection
