<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
@php
    $cloudName    = $field->getCloudName();
    $uploadPreset = $field->getUploadPreset();
    $folder       = $field->getFolder();
    $statePath    = $getStatePath();
    $state        = $getState() ?? [];
    $videos       = is_array($state) ? array_values(array_filter($state, fn($f) => preg_match('/\.(mp4|mov|avi|webm|mkv|wmv|flv)$/i', $f))) : [];
    $fieldId      = 'cld-video-' . str_replace('.', '-', $statePath);
@endphp

<div
    x-data="{
        videos: @js($videos),
        statePath: '{{ $statePath }}',
        cloudName: '{{ $cloudName }}',
        uploadPreset: '{{ $uploadPreset }}',
        folder: '{{ $folder }}',
        uploading: false,
        uploadProgress: 0,

        openWidget() {
            const widget = cloudinary.createUploadWidget({
                cloudName: this.cloudName,
                uploadPreset: this.uploadPreset,
                folder: this.folder,
                resourceType: 'video',
                multiple: true,
                sources: ['local', 'url'],
                clientAllowedFormats: ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'],
                maxFileSize: 200000000, // 200MB
                showAdvancedOptions: false,
                cropping: false,
                styles: {
                    palette: {
                        window: '#1a1a2e',
                        windowBorder: '#374151',
                        tabIcon: '#ef4444',
                        menuIcons: '#9ca3af',
                        textDark: '#ffffff',
                        textLight: '#ffffff',
                        link: '#ef4444',
                        action: '#ef4444',
                        inactiveTabIcon: '#6b7280',
                        error: '#ef4444',
                        inProgress: '#ef4444',
                        complete: '#10b981',
                        sourceBg: '#111827',
                    }
                }
            }, (error, result) => {
                if (error) {
                    console.error('Cloudinary upload error:', error);
                    return;
                }
                if (result.event === 'success') {
                    // Simpan public_id ke array videos
                    const publicId = result.info.public_id + '.' + result.info.format;
                    this.videos.push(publicId);
                    this.syncState();
                }
            });
            widget.open();
        },

        removeVideo(index) {
            this.videos.splice(index, 1);
            this.syncState();
        },

        syncState() {
            // Ambil state foto yang sudah ada (foto saja, bukan video)
            const formEl = document.querySelector('[wire\\:id]');
            if (!formEl) return;
            // Dispatch ke Livewire untuk update state
            this.$dispatch('cloudinary-videos-updated', {
                statePath: this.statePath,
                videos: this.videos
            });
        },

        getThumbUrl(publicId) {
            // Hilangkan ekstensi dari public_id kalau ada
            const base = publicId.replace(/\.(mp4|mov|avi|webm|mkv|wmv|flv)$/i, '');
            return `https://res.cloudinary.com/${this.cloudName}/video/upload/so_0,w_200,h_150,c_fill,f_jpg/${base}`;
        }
    }"
    x-on:cloudinary-videos-updated.window="
        if ($event.detail.statePath === statePath) {
            videos = $event.detail.videos;
        }
    "
    id="{{ $fieldId }}"
    wire:ignore
>
    {{-- Script Cloudinary Upload Widget (load sekali) --}}
    @once
    <script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>
    @endonce

    {{-- Daftar video yang sudah diupload --}}
    <div class="grid grid-cols-2 gap-3 mb-3" x-show="videos.length > 0">
        <template x-for="(vid, idx) in videos" :key="vid">
            <div class="relative rounded-lg overflow-hidden border border-gray-700" style="aspect-ratio:16/9;">
                <img
                    :src="getThumbUrl(vid)"
                    class="w-full h-full object-cover"
                    @error="$el.src='{{ asset('assets/logo/logo.jpg') }}'"
                >
                {{-- overlay play icon --}}
                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
                    <svg class="w-8 h-8 text-white opacity-80" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </div>
                {{-- tombol hapus --}}
                <button
                    type="button"
                    @click="removeVideo(idx)"
                    class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold z-10"
                    title="Hapus video"
                >✕</button>
                {{-- nama file --}}
                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-xs px-2 py-1 truncate"
                     x-text="vid.split('/').pop()"></div>
            </div>
        </template>
    </div>

    {{-- Tombol upload --}}
    <button
        type="button"
        @click="openWidget()"
        class="flex items-center gap-2 px-4 py-2 rounded-lg border-2 border-dashed border-gray-600 hover:border-red-500 text-gray-400 hover:text-red-400 transition-colors w-full justify-center"
        style="min-height:64px;"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.361a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
        </svg>
        <span class="text-sm font-medium">Upload Video via Cloudinary</span>
    </button>

    {{-- Hidden input untuk sync ke Livewire --}}
    <input
        type="hidden"
        x-bind:value="JSON.stringify(videos)"
        wire:model="{{ $statePath }}"
        id="{{ $fieldId }}-input"
    >

    {{-- Listener: saat videos berubah, update hidden input dan trigger Livewire --}}
    <div
        x-effect="
            const el = document.getElementById('{{ $fieldId }}-input');
            if (el) {
                el.value = JSON.stringify(videos);
                el.dispatchEvent(new Event('input'));
            }
        "
    ></div>

</div>
</x-dynamic-component>
