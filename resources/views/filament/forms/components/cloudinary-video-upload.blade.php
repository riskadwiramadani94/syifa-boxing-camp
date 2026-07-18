@php
    $cloudName    = $field->getCloudName();
    $uploadPreset = $field->getUploadPreset();
    $folder       = $field->getFolder();
    $statePath    = $getStatePath();
    $state        = $getState() ?? [];
    $videos       = is_array($state) ? array_values(array_filter($state, fn($f) => preg_match('/\.(mp4|mov|avi|webm|mkv|wmv|flv)$/i', $f))) : [];
    $fieldId      = 'cld-video-' . str_replace(['.', '[', ']'], '-', $statePath);
@endphp

<div
    x-data="{
        videos: @js($videos),
        cloudName: '{{ $cloudName }}',
        uploadPreset: '{{ $uploadPreset }}',
        folder: '{{ $folder }}',

        openWidget() {
            const widget = cloudinary.createUploadWidget({
                cloudName: this.cloudName,
                uploadPreset: this.uploadPreset,
                folder: this.folder,
                resourceType: 'video',
                multiple: true,
                sources: ['local'],
                clientAllowedFormats: ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv'],
                maxFileSize: 200000000,
                showAdvancedOptions: false,
                cropping: false,
            }, (error, result) => {
                if (error) { console.error(error); return; }
                if (result.event === 'success') {
                    const publicId = result.info.public_id + '.' + result.info.format;
                    this.videos.push(publicId);
                    this.updateLivewire();
                }
            });
            widget.open();
        },

        removeVideo(index) {
            this.videos.splice(index, 1);
            this.updateLivewire();
        },

        updateLivewire() {
            const el = document.getElementById('{{ $fieldId }}-hidden');
            if (el) {
                el.value = JSON.stringify(this.videos);
                el.dispatchEvent(new Event('input', { bubbles: true }));
            }
        },

        thumbUrl(publicId) {
            const base = publicId.replace(/\.(mp4|mov|avi|webm|mkv|wmv|flv)$/i, '');
            return 'https://res.cloudinary.com/' + this.cloudName + '/video/upload/so_0,w_300,h_200,c_fill,f_jpg/' + base;
        }
    }"
    id="{{ $fieldId }}"
    wire:ignore
>
    @once
    <script src="https://upload-widget.cloudinary.com/global/all.js"></script>
    @endonce

    {{-- Grid preview video --}}
    <div class="grid grid-cols-2 gap-2 mb-3" x-show="videos.length > 0" x-cloak>
        <template x-for="(vid, idx) in videos" :key="vid">
            <div class="relative rounded-lg overflow-hidden border border-gray-600 bg-gray-800" style="aspect-ratio:16/9;">
                <img :src="thumbUrl(vid)" class="w-full h-full object-cover"
                     :onerror="'this.src=`{{ asset('assets/logo/logo.jpg') }}`'">
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <svg class="w-8 h-8 text-white opacity-70" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </div>
                <button type="button" @click="removeVideo(idx)"
                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center z-10">✕</button>
                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-xs px-1 py-0.5 truncate"
                     x-text="vid.split('/').pop()"></div>
            </div>
        </template>
    </div>

    {{-- Tombol upload --}}
    <button type="button" @click="openWidget()"
        class="w-full flex items-center justify-center gap-2 border-2 border-dashed border-gray-500 hover:border-red-500 text-gray-400 hover:text-red-400 rounded-lg py-4 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        <span class="text-sm font-semibold">Upload Video via Cloudinary</span>
        <span class="text-xs text-gray-500">(ukuran bebas, langsung ke Cloudinary)</span>
    </button>

    {{-- Hidden field untuk sync ke Livewire --}}
    <input type="hidden"
           id="{{ $fieldId }}-hidden"
           wire:model="{{ $statePath }}"
           x-bind:value="JSON.stringify(videos)">
</div>
