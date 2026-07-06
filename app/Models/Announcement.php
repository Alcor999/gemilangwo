<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';

    protected $fillable = [
        'judul',
        'isi',
        'kategori',
        'is_aktif',
        'tanggal_mulai',
        'tanggal_selesai',
        'dibuat_oleh',
    ];

    protected $casts = [
        'is_aktif'        => 'boolean',
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Relasi ke user yang membuat pengumuman
     */
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Scope: hanya pengumuman yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Cek apakah pengumuman sedang berlangsung hari ini
     */
    public function getSedangBerlangsungAttribute(): bool
    {
        $today = now()->toDateString();
        $mulai = $this->tanggal_mulai?->toDateString();
        $selesai = $this->tanggal_selesai?->toDateString();

        if ($mulai > $today) return false;
        if ($selesai && $selesai < $today) return false;

        return $this->is_aktif;
    }

    /**
     * Label dan warna sesuai kategori
     */
    public function getLabelKategoriAttribute(): string
    {
        return match ($this->kategori) {
            'info'       => 'Informasi',
            'promo'      => 'Promosi',
            'peringatan' => 'Peringatan',
            'event'      => 'Event',
            default      => 'Lainnya',
        };
    }

    public function getWarnaBadgeAttribute(): string
    {
        return match ($this->kategori) {
            'info'       => 'bg-sky-50 text-sky-600 border-sky-100',
            'promo'      => 'bg-emerald-50 text-emerald-600 border-emerald-100',
            'peringatan' => 'bg-rose-50 text-rose-600 border-rose-100',
            'event'      => 'bg-amber-50 text-amber-600 border-amber-100',
            default      => 'bg-stone-50 text-stone-400 border-stone-100',
        };
    }
}
