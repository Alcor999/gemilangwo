@extends('layouts.app')

@section('title', 'Buat Tiket Dukungan Baru')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle"></i> Buat Tiket Dukungan Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('customer.support.tickets.store') }}" id="supportForm">
                        @csrf

                        <!-- Subject -->
                        <div class="mb-3">
                            <label for="subject" class="form-label">
                                <i class="fas fa-heading"></i> Subjek <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                id="subject" name="subject" value="{{ old('subject') }}"
                                placeholder="Deskripsi singkat masalah Anda" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label">
                                <i class="fas fa-list"></i> Kategori <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('category') is-invalid @enderror"
                                id="category" name="category" required>
                                <option value="">Pilih Kategori...</option>
                                <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>Pertanyaan Umum</option>
                                <option value="order" {{ old('category') === 'order' ? 'selected' : '' }}>Masalah Pesanan</option>
                                <option value="payment" {{ old('category') === 'payment' ? 'selected' : '' }}>Masalah Pembayaran</option>
                                <option value="complaint" {{ old('category') === 'complaint' ? 'selected' : '' }}>Keluhan</option>
                                <option value="suggestion" {{ old('category') === 'suggestion' ? 'selected' : '' }}>Saran</option>
                                <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Priority -->
                        <div class="mb-3">
                            <label for="priority" class="form-label">
                                <i class="fas fa-exclamation-triangle"></i> Prioritas <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('priority') is-invalid @enderror"
                                id="priority" name="priority" required>
                                <option value="">Pilih Prioritas...</option>
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Rendah</option>
                                <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }} selected>Sedang</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Tinggi</option>
                                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Mendesak</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Related Order (Optional) -->
                        @php
                            $userOrders = auth()->user()->orders()->get();
                        @endphp
                        @if ($userOrders->isNotEmpty())
                            <div class="mb-3">
                                <label for="order_id" class="form-label">
                                    <i class="fas fa-shopping-bag"></i> Pesanan Terkait (Opsional)
                                </label>
                                <select class="form-select @error('order_id') is-invalid @enderror"
                                    id="order_id" name="order_id">
                                    <option value="">Tidak ada pesanan terkait</option>
                                    @foreach ($userOrders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                            Order #{{ $order->id }} - {{ $order->package->name }} ({{ $order->created_at->format('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('order_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i> Deskripsi Detail <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="6"
                                placeholder="Jelaskan masalah Anda secara detail..." required>{{ old('description') }}</textarea>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i>
                                Semakin detail penjelasan Anda, semakin cepat kami dapat membantu.
                            </small>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                            <a href="{{ route('customer.support.tickets.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Buat Tiket
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="alert alert-info mt-4" role="alert">
                <h6 class="alert-heading">
                    <i class="fas fa-lightbulb"></i> Tips
                </h6>
                <ul class="mb-0 ps-3">
                    <li>Jelaskan masalah Anda dengan detail untuk respons yang lebih cepat</li>
                    <li>Sertakan nomor pesanan jika masalah terkait dengan pesanan</li>
                    <li>Tim support kami siap membantu Anda 24/7</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
    }

    .card-header {
        border-radius: 8px 8px 0 0;
    }
</style>
@endsection
