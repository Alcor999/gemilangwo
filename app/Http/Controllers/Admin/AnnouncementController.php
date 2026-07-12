<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Tampilkan daftar semua pengumuman.
     */
    public function index()
    {
        $announcements = Announcement::with('pembuat')
            ->latest()
            ->paginate(10);

        $totalAktif = Announcement::where('is_aktif', true)->count();
        $total = Announcement::count();

        return view('admin.announcements.index', compact('announcements', 'totalAktif', 'total'));
    }

    /**
     * Tampilkan form buat pengumuman baru.
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Simpan pengumuman baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'           => 'required|string|max:255',
            'isi'             => 'required|string',
            'kategori'        => 'required|in:info,promo,peringatan,event',
            'is_aktif'        => 'nullable|boolean',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $validated['dibuat_oleh'] = auth()->id();
        $validated['is_aktif'] = $request->has('is_aktif') ? 1 : 0;

        Announcement::create($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat!');
    }

    /**
     * Tampilkan detail satu pengumuman.
     */
    public function show(Announcement $announcement)
    {
        $announcement->load('pembuat');

        return view('admin.announcements.show', compact('announcement'));
    }

    /**
     * Tampilkan form edit pengumuman.
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update pengumuman di database.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'judul'           => 'required|string|max:255',
            'isi'             => 'required|string',
            'kategori'        => 'required|in:info,promo,peringatan,event',
            'is_aktif'        => 'nullable|boolean',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $validated['is_aktif'] = $request->has('is_aktif') ? 1 : 0;

        $announcement->update($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    /**
     * Hapus pengumuman dari database.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus!');
    }
}