@php
    $files = is_array($state) ? $state : [];
    $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];
    $youtubeLinks = [];
    $videoFiles = [];

    foreach ($files as $file) {
        if (str_contains($file, 'youtube.com') || str_contains($file, 'youtu.be')) {
            preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $file, $m);
            if (!empty($m[1])) {
                $youtubeLinks[] = ['id' => $m[1], 'url' => $file];
            }
        } elseif (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $videoExts)) {
            $videoFiles[] = $file;
        }
    }
@endphp

<div class="space-y-4">
    {{-- YouTube embeds --}}
    @foreach ($youtubeLinks as $yt)
        <div class="rounded-lg overflow-hidden">
            <iframe
                src="https://www.youtube.com/embed/{{ $yt['id'] }}"
                class="w-full"
                style="aspect-ratio: 16/9;"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
            ></iframe>
        </div>
    @endforeach

    {{-- Video file uploads --}}
    @foreach ($videoFiles as $file)
        <div class="rounded-lg overflow-hidden bg-black">
            <video
                controls
                class="w-full"
                style="max-height: 320px;"
            >
                <source src="{{ $file }}" type="video/mp4">
                Browser tidak mendukung video.
            </video>
        </div>
    @endforeach

    {{-- Fallback jika tidak ada video --}}
    @if (empty($youtubeLinks) && empty($videoFiles))
        <div class="flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 p-8 text-gray-400">
            <x-heroicon-o-film class="w-10 h-10 mr-2" />
            <span>Tidak ada video</span>
        </div>
    @endif
</div>
