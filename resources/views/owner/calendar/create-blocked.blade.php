@extends('layouts.app')

@section('title', 'Blokir Tanggal - ' . $package->name)

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div style="background: #f8fafc; padding: 1.5rem 0; margin: 0 0 2rem; margin-left: -1rem; margin-right: -1rem; padding-left: 1rem; padding-right: 1rem; border-bottom: 1px solid #e2e8f0;">
        <div style="max-width: 1200px; margin: 0 auto;">
            <a href="{{ route('owner.calendar.index', ['package_id' => $package->id]) }}" style="color: #3b82f6; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; font-size: 0.95rem;">
                <i class="fas fa-arrow-left"></i> Kembali ke Kalender
            </a>
            <h1 style="font-size: 2rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-ban" style="color: #ef4444;"></i> Blokir Tanggal
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.95rem;">{{ $package->name }}</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <!-- Form Card -->
            <div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                <form action="{{ route('owner.calendar.blocked.store', $package) }}" method="POST">
                    @csrf

                    <!-- Start Date -->
                    <div style="margin-bottom: 1.5rem;">
                        <label for="start_date" style="display: block; font-size: 0.95rem; font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">
                            Tanggal Mulai <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" 
                               style="width: 100%; padding: 0.75rem; border: 1px solid @error('start_date') #ef4444 @else #cbd5e1 @enderror; border-radius: 0.375rem; font-size: 1rem; transition: all 0.2s;"
                               required>
                        @error('start_date')
                            <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #ef4444;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div style="margin-bottom: 1.5rem;">
                        <label for="end_date" style="display: block; font-size: 0.95rem; font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">
                            Tanggal Akhir <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" 
                               style="width: 100%; padding: 0.75rem; border: 1px solid @error('end_date') #ef4444 @else #cbd5e1 @enderror; border-radius: 0.375rem; font-size: 1rem; transition: all 0.2s;"
                               required>
                        @error('end_date')
                            <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #ef4444;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Block Type -->
                    <div style="margin-bottom: 1.5rem;">
                        <label for="block_type" style="display: block; font-size: 0.95rem; font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">
                            Jenis Blokir <span style="color: #ef4444;">*</span>
                        </label>
                        <select id="block_type" name="block_type" 
                                style="width: 100%; padding: 0.75rem; border: 1px solid @error('block_type') #ef4444 @else #cbd5e1 @enderror; border-radius: 0.375rem; font-size: 1rem; background: white; transition: all 0.2s;"
                                required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="unavailable" {{ old('block_type') === 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                            <option value="maintenance" {{ old('block_type') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="reserved" {{ old('block_type') === 'reserved' ? 'selected' : '' }}>Dipesan</option>
                            <option value="personal" {{ old('block_type') === 'personal' ? 'selected' : '' }}>Personal</option>
                        </select>
                        @error('block_type')
                            <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #ef4444;">{{ $message }}</p>
                        @enderror
                        <div style="margin-top: 1rem; padding: 1rem; background: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 0.375rem;">
                            <p style="margin: 0 0 0.5rem 0; font-size: 0.9rem; color: #1e40af;">
                                <strong>Tidak Tersedia:</strong> Paket tidak bisa dipesan
                            </p>
                            <p style="margin: 0 0 0.5rem 0; font-size: 0.9rem; color: #1e40af;">
                                <strong>Maintenance:</strong> Sedang dalam perbaikan
                            </p>
                            <p style="margin: 0 0 0.5rem 0; font-size: 0.9rem; color: #1e40af;">
                                <strong>Dipesan:</strong> Sudah dipesan atau dipegang
                            </p>
                            <p style="margin: 0; font-size: 0.9rem; color: #1e40af;">
                                <strong>Personal:</strong> Alasan personal
                            </p>
                        </div>
                    </div>

                    <!-- Reason -->
                    <div style="margin-bottom: 1.5rem;">
                        <label for="reason" style="display: block; font-size: 0.95rem; font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">
                            Alasan (Opsional)
                        </label>
                        <textarea id="reason" name="reason" rows="3" placeholder="Misalnya: Liburan keluarga, perbaikan peralatan, dll."
                                  style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; font-size: 1rem; font-family: inherit; transition: all 0.2s; resize: vertical;"></textarea>
                        @error('reason')
                            <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #ef4444;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div style="margin-bottom: 1.5rem; padding: 1rem; background: #dbeafe; border-left: 4px solid #0ea5e9; border-radius: 0.375rem;">
                        <p style="margin: 0; font-size: 0.95rem; color: #0c4a6e;">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Catatan:</strong> Tanggal yang diblokir tidak akan tersedia untuk pemesanan oleh pelanggan.
                        </p>
                    </div>

                    <!-- Form Actions -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <button type="submit" style="padding: 0.75rem 1.5rem; background: #ef4444; color: white; border: none; border-radius: 0.375rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <i class="fas fa-ban"></i> Blokir Tanggal
                        </button>
                        <a href="{{ route('owner.calendar.index', ['package_id' => $package->id]) }}" 
                           style="padding: 0.75rem 1.5rem; background: #e5e7eb; color: #1f2937; border: none; border-radius: 0.375rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.2s; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Set minimum end date equal to start date
    document.getElementById('start_date').addEventListener('change', function() {
        document.getElementById('end_date').min = this.value;
        if (document.getElementById('end_date').value < this.value) {
            document.getElementById('end_date').value = this.value;
        }
    });
</script>
@endsection
