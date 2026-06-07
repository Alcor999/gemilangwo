@extends('layouts.app')

@section('title', 'Manajemen Delegasi & Pengguna - Administrator')

@section('content')
<div class="space-y-12 pb-24">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
        <div class="space-y-2">
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.4em]">Client Base & Access</p>
            <h1 class="font-serif text-4xl text-choco-900 italic">Direktori <span class="not-italic text-stone-300">Pengguna</span></h1>
        </div>
        
        <x-luxury.button href="{{ route('admin.users.index') }}" variant="secondary" class="h-12 px-8">
            <i class="fas fa-sync-alt mr-2 text-[10px]"></i>
            Refresh Manifest
        </x-luxury.button>
    </div>

    <!-- Main Content Table -->
    <x-luxury.card class="overflow-hidden border-stone-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-stone-50/50 border-b border-stone-100">
                        <th class="px-8 py-6 text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400">Persona & Identitas</th>
                        <th class="px-8 py-6 text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400">Kontak Elektronik</th>
                        <th class="px-8 py-6 text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400">Otoritas Peran</th>
                        <th class="px-8 py-6 text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400 text-center">Lifecycle</th>
                        <th class="px-8 py-6 text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400 text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($users as $user)
                        <tr class="group hover:bg-stone-50/30 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-full bg-stone-100 flex items-center justify-center text-choco-900 font-bold text-xs border border-stone-200">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="space-y-0.5">
                                        <p class="text-sm font-bold text-choco-900 group-hover:text-gold-600 transition-colors">{{ $user->name }}</p>
                                        <p class="text-[9px] text-stone-400 font-medium uppercase tracking-widest">{{ $user->phone ?? 'Unlisted Contact' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-xs text-stone-500 font-light italic">
                                {{ $user->email }}
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $roleClasses = [
                                        'admin' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        'owner' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'user' => 'bg-gold-50 text-gold-600 border-gold-100',
                                        'default' => 'bg-stone-50 text-stone-500 border-stone-100'
                                    ];
                                    $roleClass = $roleClasses[$user->role] ?? $roleClasses['default'];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border {{ $roleClass }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border {{ $user->deleted_at ? 'bg-stone-50 text-stone-300 border-stone-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }}">
                                    {{ $user->deleted_at ? 'Archived' : 'Active' }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="h-9 w-9 flex items-center justify-center rounded-xl bg-stone-50 text-stone-400 hover:bg-choco-900 hover:text-gold-400 transition-all border border-stone-100 hover:border-choco-900" 
                                       title="Detailed Profile">
                                        <i class="fas fa-id-card text-[10px]"></i>
                                    </a>
                                    
                                    @if(!$user->deleted_at)
                                        <form action="{{ route('admin.users.deactivate', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="h-9 w-9 flex items-center justify-center rounded-xl bg-stone-50 text-stone-400 hover:bg-rose-500 hover:text-white transition-all border border-stone-100 hover:border-rose-500"
                                                    data-confirm="Terminasi akses pengguna &quot;{{ $user->name }}&quot;?"
                                                    data-confirm-title="Terminasi Pengguna"
                                                    data-confirm-btn="Terminasi Akses"
                                                    data-confirm-danger="1">
                                                <i class="fas fa-user-slash text-[10px]"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="space-y-4">
                                    <div class="h-16 w-16 bg-stone-50 rounded-3xl mx-auto flex items-center justify-center text-stone-200">
                                        <i class="fas fa-user-group text-2xl"></i>
                                    </div>
                                    <p class="text-stone-400 font-serif italic text-sm">Belum ada delegasi pengguna terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginasi -->
        @if($users->hasPages())
            <div class="px-8 py-6 border-t border-stone-50 bg-stone-50/30">
                {{ $users->links() }}
            </div>
        @endif
    </x-luxury.card>
</div>
@endsection
