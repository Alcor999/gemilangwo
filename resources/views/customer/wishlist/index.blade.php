@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="h3 fw-bold mb-4">
        <i class="fas fa-heart text-danger"></i> Wishlist Saya
    </h2>

    @if($wishlists->count() > 0)
        <div class="row g-4">
            @foreach($wishlists as $wishlist)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 position-relative">
                        <div class="card-img-top position-relative" style="height: 200px; overflow: hidden;">
                            @if($wishlist->package->image)
                                <img src="{{ Storage::url($wishlist->package->image) }}" alt="{{ $wishlist->package->name }}" 
                                     class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted fa-3x"></i>
                                </div>
                            @endif
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-2 end-2" 
                                    onclick="removeFromWishlist({{ $wishlist->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $wishlist->package->name }}</h5>
                            <p class="text-muted small mb-2">{{ Str::limit($wishlist->package->description, 60) }}</p>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    @if($wishlist->package->getTotalReviews() > 0)
                                        <div class="text-warning small">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $wishlist->package->getAverageRating() ? '' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="small text-muted">({{ $wishlist->package->getTotalReviews() }})</span>
                                    @endif
                                </div>
                            </div>

                            <h4 class="text-primary fw-bold mb-3">
                                Rp {{ number_format($wishlist->package->getDiscountedPrice(), 0, ',', '.') }}
                            </h4>

                            <div class="d-grid gap-2">
                                <a href="{{ route('customer.packages.show', $wishlist->package) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                                <a href="{{ route('customer.orders.create', ['package_id' => $wishlist->package->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                                </a>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-top small text-muted">
                            Ditambahkan: {{ $wishlist->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($wishlists->hasPages())
            <div class="mt-5">
                {{ $wishlists->links() }}
            </div>
        @endif
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
            <h5>Wishlist Anda Masih Kosong</h5>
            <p class="text-muted mb-3">Tambahkan paket favorit ke wishlist untuk menyimpannya.</p>
            <a href="{{ route('customer.packages.index') }}" class="btn btn-primary">
                <i class="fas fa-search"></i> Jelajahi Paket
            </a>
        </div>
    @endif
</div>

<script>
function removeFromWishlist(wishlistId) {
    if (confirm('Hapus dari wishlist?')) {
        fetch(`/customer/wishlist/${wishlistId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}
</script>
@endsection
