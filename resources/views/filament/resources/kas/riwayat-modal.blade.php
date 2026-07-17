<div>
    @if(count($rows) === 0)
        <div style="padding:32px; text-align:center; color:#6b7280;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:40px;height:40px;margin:0 auto 12px;color:#374151;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p style="font-size:14px;">Belum ada riwayat pembayaran di tahun {{ $tahun }}.</p>
        </div>
    @else
        {{-- Ringkasan --}}
        <div style="display:flex; gap:12px; margin-bottom:16px; flex-wrap:wrap;">
            <div style="flex:1; min-width:140px; padding:12px 16px; border-radius:10px;
                        background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.2);">
                <p style="font-size:11px; color:#9ca3af; margin:0 0 4px;">Bulan Lunas</p>
                <p style="font-size:20px; font-weight:700; color:#4ade80; margin:0;">{{ $totalLunas }} bulan</p>
            </div>
            <div style="flex:1; min-width:140px; padding:12px 16px; border-radius:10px;
                        background:rgba(245,158,11,0.1); border:1px solid rgba(245,158,11,0.2);">
                <p style="font-size:11px; color:#9ca3af; margin:0 0 4px;">Total Terbayar</p>
                <p style="font-size:20px; font-weight:700; color:#fbbf24; margin:0;">
                    Rp {{ number_format($totalNominal, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Tabel riwayat --}}
        <div style="border-radius:10px; border:1px solid rgba(255,255,255,0.08); overflow:hidden;">
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <thead>
                    <tr style="background:rgba(255,255,255,0.05); border-bottom:1px solid rgba(255,255,255,0.08);">
                        <th style="text-align:left; padding:10px 14px; color:#9ca3af; font-weight:600;">Bulan</th>
                        <th style="text-align:left; padding:10px 14px; color:#9ca3af; font-weight:600;">Tanggal</th>
                        <th style="text-align:right; padding:10px 14px; color:#9ca3af; font-weight:600;">Nominal</th>
                        <th style="text-align:center; padding:10px 14px; color:#9ca3af; font-weight:600;">Status</th>
                        <th style="text-align:left; padding:10px 14px; color:#9ca3af; font-weight:600;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $i => $r)
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.05);
                                   background:{{ $i % 2 === 1 ? 'rgba(255,255,255,0.02)' : 'transparent' }};">
                            <td style="padding:10px 14px; color:#f9fafb; font-weight:500;">{{ $r['bulan'] }}</td>
                            <td style="padding:10px 14px; color:#d1d5db;">{{ $r['tanggal'] }}</td>
                            <td style="padding:10px 14px; color:#f9fafb; font-weight:600; text-align:right;">{{ $r['nominal'] }}</td>
                            <td style="padding:10px 14px; text-align:center;">
                                <span style="display:inline-block; padding:2px 10px; border-radius:999px;
                                             font-size:11px; font-weight:600;
                                             color:{{ $r['status_color'] }};
                                             background:{{ $r['status'] === 'Lunas' ? 'rgba(34,197,94,0.12)' : 'rgba(234,179,8,0.12)' }};
                                             border:1px solid {{ $r['status_color'] }}40;">
                                    {{ $r['status'] }}
                                </span>
                            </td>
                            <td style="padding:10px 14px; color:#9ca3af; font-size:12px;">{{ $r['keterangan'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
