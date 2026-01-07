@extends('layouts.app')

@section('title', 'Discount Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-eye"></i> Discount Details</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $discount->name }}</h5>
                    @if ($discount->description)
                        <p class="text-muted">{{ $discount->description }}</p>
                    @endif

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Discount Type</h6>
                            <p>
                                @if ($discount->type === 'percentage')
                                    <span class="badge bg-info">Percentage</span> {{ $discount->value }}%
                                @else
                                    <span class="badge bg-info">Fixed Amount</span> Rp {{ number_format($discount->value, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted">Status</h6>
                            <p>
                                @if ($discount->is_active)
                                    <span class="badge bg-success">Enabled</span>
                                @else
                                    <span class="badge bg-danger">Disabled</span>
                                @endif
                                
                                @if ($discount->isActive())
                                    <span class="badge bg-success">Currently Active</span>
                                @else
                                    <span class="badge bg-warning">Not Active Yet</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Start Date</h6>
                            <p>{{ $discount->start_date->format('d F Y H:i') }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted">End Date</h6>
                            <p>
                                @if ($discount->end_date)
                                    {{ $discount->end_date->format('d F Y H:i') }}
                                @else
                                    <span class="text-muted">No expiry (unlimited)</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($discount->usage_limit)
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6 class="text-muted">Usage Limit</h6>
                                <p>{{ $discount->usage_limit }} times</p>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-muted">Usage Count</h6>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ ($discount->usage_count / $discount->usage_limit) * 100 }}%"
                                         aria-valuenow="{{ $discount->usage_count }}" aria-valuemin="0" 
                                         aria-valuemax="{{ $discount->usage_limit }}">
                                        {{ $discount->usage_count }} / {{ $discount->usage_limit }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($discount->packages->count() > 0)
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Applied Packages</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Package Name</th>
                                    <th>Original Price</th>
                                    <th>Discount</th>
                                    <th>Final Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($discount->packages as $package)
                                    <tr>
                                        <td><strong>{{ $package->name }}</strong></td>
                                        <td>Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($discount->type === 'percentage')
                                                {{ $discount->value }}% (Rp {{ number_format($discount->calculateDiscount($package->price), 0, ',', '.') }})
                                            @else
                                                Rp {{ number_format(min($discount->value, $package->price), 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td><strong>Rp {{ number_format($discount->getDiscountedPrice($package->price), 0, ',', '.') }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center text-muted">
                        <p class="mb-0">This discount applies to <strong>all packages</strong></p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Info</h5>
                </div>
                <div class="card-body">
                    <p class="small">
                        <strong>Created by:</strong><br>
                        {{ $discount->creator->name }}<br>
                        <span class="text-muted">{{ $discount->created_at->format('d M Y H:i') }}</span>
                    </p>

                    <p class="small">
                        <strong>Last Updated:</strong><br>
                        <span class="text-muted">{{ $discount->updated_at->format('d M Y H:i') }}</span>
                    </p>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100" 
                                    onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
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

    .card-title {
        color: #8b5cf6;
        font-weight: 600;
    }
</style>
@endsection
