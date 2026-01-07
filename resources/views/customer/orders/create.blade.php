@extends('layouts.app')

@section('title', 'Create New Booking')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Create New Wedding Event Booking</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.orders.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="package_id" class="form-label">Select Package *</label>
                            <select class="form-select @error('package_id') is-invalid @enderror" id="package_id" name="package_id" required>
                                <option value="">-- Select a package --</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('package_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="event_date" class="form-label">Event Date *</label>
                            <input type="date" class="form-control @error('event_date') is-invalid @enderror" id="event_date" name="event_date" value="{{ old('event_date') }}" required>
                            @error('event_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="event_location" class="form-label">Event Location *</label>
                            <input type="text" class="form-control @error('event_location') is-invalid @enderror" id="event_location" name="event_location" placeholder="Venue/Ballroom Name" value="{{ old('event_location') }}" required>
                            @error('event_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="guest_count" class="form-label">Number of Guests *</label>
                            <input type="number" class="form-control @error('guest_count') is-invalid @enderror" id="guest_count" name="guest_count" min="1" value="{{ old('guest_count') }}" required>
                            @error('guest_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="special_request" class="form-label">Special Requests (Optional)</label>
                            <textarea class="form-control" id="special_request" name="special_request" rows="4" placeholder="Let us know about any special requirements or customizations...">{{ old('special_request') }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Create Booking
                            </button>
                            <a href="{{ route('customer.packages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">Booking Information</h6>
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-info-circle me-2"></i>Please fill all required fields marked with *</li>
                        <li><i class="fas fa-calendar-alt me-2"></i>Event date must be at least 1 week in advance</li>
                        <li><i class="fas fa-credit-card me-2"></i>You'll proceed to payment after creating the booking</li>
                        <li><i class="fas fa-handshake me-2"></i>We'll confirm your booking within 24 hours</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
