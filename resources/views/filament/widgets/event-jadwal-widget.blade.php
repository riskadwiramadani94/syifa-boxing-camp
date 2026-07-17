<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Event &amp; Jadwal</x-slot>
        <x-slot name="headerEnd">
            <span class="text-xs text-gray-500 font-normal">{{ now()->translatedFormat('l, d M Y') }}</span>
        </x-slot>

        <div class="space-y-5">

            {{-- ── JADWAL LATIHAN HARI INI ── --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-block w-2 h-2 rounded-full bg-amber-400" style="animation: pulseDot 1.8s ease-in-out infinite;"></span>
                    <p class="text-xs font-semibold text-amber-400 uppercase tracking-wider">
                        Latihan Hari Ini &mdash; {{ $hariIni }}
                    </p>
                </div>

                @if($jadwalHariIni->isEmpty())
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10"
                         style="animation: dashFadeUp 0.5s ease 0.3s both; opacity: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0" style="width:18px;height:18px;color:#6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-gray-400">Tidak ada jadwal latihan hari ini</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($jadwalHariIni as $index => $jadwal)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 transition-all duration-200 hover:bg-amber-500/20 hover:-translate-y-0.5"
                                 style="animation: dashFadeUp 0.45s ease {{ 0.25 + $index * 0.1 }}s both; opacity: 0;">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="shrink-0 w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#fbbf24;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-white truncate">{{ $jadwal->kelas }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ $jadwal->pelatih ?? 'Pelatih' }}</p>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2">
                                    <span class="text-xs font-mono text-amber-400 bg-amber-500/10 px-2 py-1 rounded-lg whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                        &ndash;
                                        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- DIVIDER --}}
            <div class="border-t border-white/10"></div>

            {{-- ── EVENT MENDATANG ── --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-block w-2 h-2 rounded-full bg-blue-400"></span>
                    <p class="text-xs font-semibold text-blue-400 uppercase tracking-wider">
                        Event Mendatang
                    </p>
                </div>

                @if($events->isEmpty())
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10"
                         style="animation: dashFadeUp 0.5s ease 0.5s both; opacity: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0" style="width:18px;height:18px;color:#6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-400">Tidak ada event yang akan datang</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($events as $index => $event)
                            @php
                                $isOpen   = $event->status === 'dibuka';
                                $daysLeft = now()->diffInDays($event->tanggal_mulai, false);
                                $bgClass  = $isOpen
                                    ? 'bg-green-500/10 border-green-500/20 hover:bg-green-500/20'
                                    : 'bg-blue-500/10 border-blue-500/20 hover:bg-blue-500/20';
                                $iconColor = $isOpen ? '#4ade80' : '#60a5fa';
                                $iconBg    = $isOpen ? 'bg-green-500/20' : 'bg-blue-500/20';
                                $badgeClass = $isOpen
                                    ? 'text-green-400 bg-green-500/10'
                                    : 'text-blue-400 bg-blue-500/10';
                            @endphp
                            <div class="flex items-center justify-between p-3 rounded-xl border {{ $bgClass }} transition-all duration-200 hover:-translate-y-0.5"
                                 style="animation: dashFadeUp 0.45s ease {{ 0.45 + $index * 0.1 }}s both; opacity: 0;">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="shrink-0 w-8 h-8 rounded-lg {{ $iconBg }} flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:{{ $iconColor }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-white truncate">{{ $event->judul }}</p>
                                        <p class="text-xs text-gray-400">{{ $event->tanggal_mulai?->format('d M Y') }}{{ $event->tanggal_selesai && $event->tanggal_mulai?->format('d M Y') !== $event->tanggal_selesai?->format('d M Y') ? ' – ' . $event->tanggal_selesai->format('d M Y') : '' }}</p>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 text-right">
                                    @if($daysLeft >= 0)
                                        <span class="text-xs px-2 py-1 rounded-lg font-medium whitespace-nowrap {{ $badgeClass }}">
                                            {{ $daysLeft == 0 ? 'Hari Ini!' : $daysLeft . ' hari lagi' }}
                                        </span>
                                    @else
                                        <span class="text-xs px-2 py-1 rounded-lg font-medium text-gray-400 bg-gray-700/50">
                                            {{ $isOpen ? 'Dibuka' : 'Segera' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
