<x-filament-panels::page>

    {{-- ── FILTER TAHUN ── --}}
    <x-filament::section>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <span style="font-size:13px; color:#9ca3af;">Tahun:</span>
            @php $tahunRange = range(now()->year - 2, now()->year + 1); @endphp
            @foreach($tahunRange as $y)
                <button wire:click="gantiTahun({{ $y }})"
                        style="padding:4px 14px; border-radius:8px; font-size:13px; font-weight:500; border:1px solid;
                               transition:all 0.15s;
                               {{ $tahun == $y
                                   ? 'background:#f59e0b; color:#000; border-color:#f59e0b;'
                                   : 'background:rgba(255,255,255,0.07); color:#d1d5db; border-color:rgba(255,255,255,0.1);' }}">
                    {{ $y }}
                </button>
            @endforeach
        </div>
    </x-filament::section>

    {{-- ── TABEL KAS ── --}}
    <x-filament::section>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <thead>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">
                        <th style="text-align:left; padding:10px 16px; color:#9ca3af; font-weight:600; min-width:160px;">
                            Nama Anggota
                        </th>
                        @foreach($this->namaBulan() as $no => $nama)
                            <th style="text-align:center; padding:10px 6px; color:#9ca3af; font-weight:600; min-width:50px;">
                                {{ substr($nama, 0, 3) }}
                            </th>
                        @endforeach
                        <th style="text-align:center; padding:10px 16px; color:#9ca3af; font-weight:600; min-width:80px;">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->anggotaList as $anggota)
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.05);"
                            onmouseover="this.style.background='rgba(255,255,255,0.03)'"
                            onmouseout="this.style.background=''">

                            {{-- Nama --}}
                            <td style="padding:10px 16px; color:#f9fafb; font-weight:500;">
                                {{ $anggota->nama_lengkap }}
                            </td>

                            {{-- Kolom per bulan --}}
                            @foreach($this->namaBulan() as $no => $nama)
                                @php
                                $kas     = $anggota->kas->where('bulan', $no)->first();
                                // Prioritas: kalau sudah_bayar true tapi status_bayar masih 'belum' → anggap lunas
                                if (! $kas) {
                                    $status = 'belum';
                                } elseif ($kas->status_bayar === 'cicil') {
                                    $status = 'cicil';
                                } elseif ($kas->status_bayar === 'lunas' || $kas->sudah_bayar) {
                                    $status = 'lunas';
                                } else {
                                    $status = 'belum';
                                }
                                $dibayar = $kas ? (int) $kas->jumlah_dibayar : 0;
                            @endphp
                                <td style="padding:6px 4px; text-align:center; vertical-align:middle;">
                                    <button wire:click="bukaModal({{ $anggota->id }}, {{ $no }})"
                                            title="Klik untuk bayar {{ $nama }}"
                                            style="display:inline-flex; flex-direction:column; align-items:center;
                                                   background:none; border:none; cursor:pointer; padding:4px;
                                                   border-radius:6px; transition:transform 0.15s;"
                                            onmouseover="this.style.transform='scale(1.2)'"
                                            onmouseout="this.style.transform='scale(1)'">

                                        @if($status === 'lunas')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#22c55e" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @elseif($status === 'cicil')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#eab308" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif

                                        @if($dibayar > 0)
                                            <span style="font-size:9px; color:#9ca3af; margin-top:2px; white-space:nowrap;">
                                                Rp {{ number_format($dibayar, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </button>
                                </td>
                            @endforeach

                            {{-- Total bulan lunas --}}
                            <td style="padding:10px 16px; text-align:center;">
                                @php $totalLunas = $anggota->kas->where('sudah_bayar', true)->count(); @endphp
                                <span style="display:inline-block; padding:2px 10px; border-radius:999px; font-size:11px; font-weight:600;
                                             {{ $totalLunas > 0 ? 'background:rgba(34,197,94,0.15); color:#4ade80;' : 'background:rgba(255,255,255,0.07); color:#6b7280;' }}">
                                    {{ $totalLunas }} bulan
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" style="padding:32px; text-align:center; color:#6b7280;">
                                Tidak ada anggota aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>

    {{-- ── MODAL BAYAR ── --}}
    @if($showModal)
        <div style="position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center;
                    padding:16px; background:rgba(0,0,0,0.65);"
             wire:click.self="tutupModal">

            <div style="width:100%; max-width:440px; border-radius:16px; border:1px solid rgba(255,255,255,0.12);
                        background:#111827; box-shadow:0 25px 60px rgba(0,0,0,0.5);
                        animation: dashFadeUp 0.25s ease both;">

                {{-- Header --}}
                <div style="display:flex; align-items:center; justify-content:space-between;
                             padding:16px 20px; border-bottom:1px solid rgba(255,255,255,0.08);">
                    <div>
                        <p style="font-size:15px; font-weight:600; color:#f9fafb; margin:0;">Pembayaran Kas</p>
                        <p style="font-size:13px; color:#f59e0b; margin:4px 0 0;">
                            {{ $modalNama }} — {{ $modalBulanLabel }} {{ $tahun }}
                        </p>
                    </div>
                    <button wire:click="tutupModal"
                            style="background:none; border:none; cursor:pointer; color:#6b7280; padding:4px;"
                            onmouseover="this.style.color='#f9fafb'"
                            onmouseout="this.style.color='#6b7280'">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Info & riwayat --}}
                @php $info = $this->getModalKasInfo(); @endphp
                @if($info['total'] > 0)
                    <div style="margin:12px 20px 0; padding:12px 14px; border-radius:10px;
                                background:rgba(245,158,11,0.1); border:1px solid rgba(245,158,11,0.2);">
                        <p style="font-size:13px; color:#fbbf24; margin:0;">
                            Sudah terbayar: <strong>Rp {{ number_format($info['total'], 0, ',', '.') }}</strong>
                        </p>
                        @if(count($info['riwayat']) > 0)
                            <div style="margin-top:8px; display:flex; flex-direction:column; gap:4px;">
                                @foreach($info['riwayat'] as $r)
                                    <div style="display:flex; justify-content:space-between; font-size:11px; color:#9ca3af;">
                                        <span>
                                            {{ \Carbon\Carbon::parse($r['tanggal_bayar'])->format('d M Y') }}
                                            @if($r['keterangan']) — {{ $r['keterangan'] }} @endif
                                        </span>
                                        <span style="color:{{ $r['status'] === 'lunas' ? '#4ade80' : '#facc15' }};">
                                            Rp {{ number_format($r['jumlah'], 0, ',', '.') }}
                                            {{ $r['status'] === 'lunas' ? '✅' : '🟡' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Form --}}
                <div style="padding:16px 20px; display:flex; flex-direction:column; gap:14px;">

                    {{-- Status toggle --}}
                    <div>
                        <label style="display:block; font-size:13px; font-weight:500; color:#d1d5db; margin-bottom:6px;">
                            Status Pembayaran
                        </label>
                        <div style="display:flex; gap:10px;">
                            <button wire:click="$set('formStatus', 'cicil')" type="button"
                                    style="flex:1; padding:8px; border-radius:10px; font-size:13px; font-weight:500;
                                           cursor:pointer; transition:all 0.15s; border:1px solid;
                                           {{ $formStatus === 'cicil'
                                               ? 'background:rgba(234,179,8,0.15); border-color:#eab308; color:#facc15;'
                                               : 'background:rgba(255,255,255,0.05); border-color:rgba(255,255,255,0.1); color:#6b7280;' }}">
                                Cicil
                            </button>
                            <button wire:click="$set('formStatus', 'lunas')" type="button"
                                    style="flex:1; padding:8px; border-radius:10px; font-size:13px; font-weight:500;
                                           cursor:pointer; transition:all 0.15s; border:1px solid;
                                           {{ $formStatus === 'lunas'
                                               ? 'background:rgba(34,197,94,0.15); border-color:#22c55e; color:#4ade80;'
                                               : 'background:rgba(255,255,255,0.05); border-color:rgba(255,255,255,0.1); color:#6b7280;' }}">
                                Lunas
                            </button>
                        </div>
                        @if(isset($formErrors['status']))
                            <p style="font-size:11px; color:#f87171; margin-top:4px;">{{ $formErrors['status'] }}</p>
                        @endif
                    </div>

                    {{-- Jumlah --}}
                    <div>
                        <label style="display:block; font-size:13px; font-weight:500; color:#d1d5db; margin-bottom:6px;">
                            Jumlah Dibayar (Rp)
                        </label>
                        <div style="position:relative;">
                            <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%);
                                         font-size:13px; color:#6b7280;">Rp</span>
                            <input type="number" wire:model="formJumlah" placeholder="Contoh: 150000" min="1"
                                   style="width:100%; padding:9px 12px 9px 36px; border-radius:10px;
                                          background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1);
                                          color:#f9fafb; font-size:13px; outline:none; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='rgba(245,158,11,0.5)'"
                                   onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                        </div>
                        @if(isset($formErrors['jumlah']))
                            <p style="font-size:11px; color:#f87171; margin-top:4px;">{{ $formErrors['jumlah'] }}</p>
                        @endif
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label style="display:block; font-size:13px; font-weight:500; color:#d1d5db; margin-bottom:6px;">
                            Tanggal Bayar
                        </label>
                        <input type="date" wire:model="formTanggal"
                               style="width:100%; padding:9px 12px; border-radius:10px;
                                      background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1);
                                      color:#f9fafb; font-size:13px; outline:none; box-sizing:border-box;"
                               onfocus="this.style.borderColor='rgba(245,158,11,0.5)'"
                               onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                        @if(isset($formErrors['tanggal']))
                            <p style="font-size:11px; color:#f87171; margin-top:4px;">{{ $formErrors['tanggal'] }}</p>
                        @endif
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label style="display:block; font-size:13px; font-weight:500; color:#d1d5db; margin-bottom:6px;">
                            Keterangan <span style="color:#6b7280;">(opsional)</span>
                        </label>
                        <input type="text" wire:model="formKeterangan"
                               placeholder="cicil pertama, pelunasan, dll." maxlength="255"
                               style="width:100%; padding:9px 12px; border-radius:10px;
                                      background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1);
                                      color:#f9fafb; font-size:13px; outline:none; box-sizing:border-box;"
                               onfocus="this.style.borderColor='rgba(245,158,11,0.5)'"
                               onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                    </div>

                </div>

                {{-- Footer --}}
                <div style="padding:0 20px 18px;">
                    <button wire:click="simpanPembayaran"
                            style="width:100%; padding:10px; border-radius:10px; font-size:13px; font-weight:600;
                                   cursor:pointer; background:#f59e0b; border:none; color:#000; transition:background 0.15s;"
                            onmouseover="this.style.background='#fbbf24'"
                            onmouseout="this.style.background='#f59e0b'">
                        Simpan Pembayaran
                    </button>
                </div>

            </div>
        </div>
    @endif

</x-filament-panels::page>
