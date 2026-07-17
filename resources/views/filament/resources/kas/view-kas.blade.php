<x-filament-panels::page>

    {{-- ── INFO ANGGOTA ── --}}
    <x-filament::section>
        <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
            <div style="width:48px; height:48px; border-radius:50%; background:rgba(245,158,11,0.15);
                        border:2px solid rgba(245,158,11,0.3); display:flex; align-items:center; justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p style="font-size:18px; font-weight:700; color:#f9fafb; margin:0;">{{ $this->anggota->nama_lengkap }}</p>
                <p style="font-size:13px; color:#9ca3af; margin:4px 0 0;">
                    {{ $this->anggota->kelas ?? '-' }}
                    @if($this->anggota->jenis_kelamin) &bull; {{ $this->anggota->jenis_kelamin }} @endif
                    @if($this->anggota->no_telepon) &bull; {{ $this->anggota->no_telepon }} @endif
                </p>
            </div>
        </div>
    </x-filament::section>

    {{-- ── FILTER TAHUN + RINGKASAN ── --}}
    <x-filament::section>
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
            {{-- Filter tahun --}}
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:13px; color:#9ca3af;">Tahun:</span>
                @foreach(range(now()->year - 2, now()->year + 1) as $y)
                    <button wire:click="gantiTahun({{ $y }})"
                            style="padding:4px 14px; border-radius:8px; font-size:13px; font-weight:500; border:1px solid; cursor:pointer;
                                   {{ $tahun == $y
                                       ? 'background:#f59e0b; color:#000; border-color:#f59e0b;'
                                       : 'background:rgba(255,255,255,0.07); color:#d1d5db; border-color:rgba(255,255,255,0.1);' }}">
                        {{ $y }}
                    </button>
                @endforeach
            </div>

            {{-- Ringkasan --}}
            @php $summary = $this->getKasSummary(); @endphp
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <div style="padding:8px 14px; border-radius:10px; background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.2);">
                    <span style="font-size:11px; color:#9ca3af;">Lunas: </span>
                    <span style="font-size:14px; font-weight:700; color:#4ade80;">{{ $summary['total_lunas'] }} bulan</span>
                </div>
                <div style="padding:8px 14px; border-radius:10px; background:rgba(234,179,8,0.1); border:1px solid rgba(234,179,8,0.2);">
                    <span style="font-size:11px; color:#9ca3af;">Cicil: </span>
                    <span style="font-size:14px; font-weight:700; color:#fbbf24;">{{ $summary['total_cicil'] }} bulan</span>
                </div>
                <div style="padding:8px 14px; border-radius:10px; background:rgba(245,158,11,0.1); border:1px solid rgba(245,158,11,0.2);">
                    <span style="font-size:11px; color:#9ca3af;">Total: </span>
                    <span style="font-size:14px; font-weight:700; color:#fbbf24;">Rp {{ number_format($summary['total_nominal'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </x-filament::section>

    {{-- ── TABEL RIWAYAT ── --}}
    <x-filament::section heading="Riwayat Pembayaran Kas">
        {{ $this->table }}
    </x-filament::section>

</x-filament-panels::page>
