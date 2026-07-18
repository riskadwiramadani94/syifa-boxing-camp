{{--
    Widget upload video langsung ke Cloudinary dari browser (bypass PHP).
    File tidak lewat server Laravel sama sekali, jadi tidak ada batasan ukuran PHP.

    SYARAT: Buat Upload Preset "unsigned" di Cloudinary Dashboard:
    Settings → Upload → Upload presets → Add upload preset
    - Signing mode: Unsigned
    - Folder: media/galeri
    Lalu isi CLOUDINARY_UPLOAD_PRESET di .env
--}}
@php
    $cloudName     = env('CLOUDINARY_CLOUD_NAME', '');
    $uploadPreset  = env('CLOUDINARY_UPLOAD_PRESET', '');
@endphp

<div
    x-data="{
        videos: [],
        uploading: false,
        uploadProgress: 0,

        addVideo(url, publicId) {
            this.videos.push({ url, publicId, name: publicId.split('/').pop() });
            this.notifyFilament();
        },

        removeVideo(idx) {
            this.videos.splice(idx, 1);
            this.notifyFilament();
        },

        notifyFilament() {
            // Trigger perubahan ke Livewire komponen Filament
            this.$dispatch('cloudinary-videos-updated', { videos: this.videos });
        },

        async uploadFile(file) {
            const cloudName = '{{ $cloudName }}';
            const preset    = '{{ $uploadPreset }}';

            if (!cloudName || !preset) {
                alert('CLOUDINARY_CLOUD_NAME dan CLOUDINARY_UPLOAD_PRESET belum diset di .env.\n\nBuat upload preset unsigned di Cloudinary Dashboard dulu.');
                return;
            }

            this.uploading = true;
            this.uploadProgress = 0;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('upload_preset', preset);
            formData.append('folder', 'media/galeri');
            formData.append('resource_type', 'video');

            const xhr = new XMLHttpRequest();
            xhr.upload.onprogress = (e) => {
                if (e.lengthComputable) {
                    this.uploadProgress = Math.round((e.loaded / e.total) * 100);
                }
            };
            xhr.onload = () => {
                this.uploading = false;
                if (xhr.status === 200) {
                    const res = JSON.parse(xhr.responseText);
                    this.addVideo(res.secure_url, res.public_id);
                } else {
                    alert('Upload gagal. Pastikan upload preset sudah dibuat dan benar.');
                }
            };
            xhr.onerror = () => {
                this.uploading = false;
                alert('Terjadi kesalahan saat upload. Coba lagi.');
            };
            xhr.open('POST', `https://api.cloudinary.com/v1_1/${cloudName}/video/upload`);
            xhr.send(formData);
        },

        handleFileSelect(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => this.uploadFile(file));
            event.target.value = '';
        },

        handleDrop(event) {
            const files = Array.from(event.dataTransfer.files).filter(f => f.type.startsWith('video/'));
            files.forEach(file => this.uploadFile(file));
        }
    }"
    @dragover.prevent
    @drop.prevent="handleDrop($event)"
    class="gd-video-upload-wrap"
>
    @if(empty($cloudName) || empty($uploadPreset))
    <div class="gd-upload-warning">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14M12 2a10 10 0 100 20A10 10 0 0012 2z"/></svg>
        <div>
            <strong>Setup diperlukan:</strong> Isi <code>CLOUDINARY_UPLOAD_PRESET</code> di .env dengan nama preset <em>unsigned</em> dari Cloudinary Dashboard.
            <br><small>Settings → Upload → Upload Presets → Add upload preset → Signing mode: Unsigned</small>
        </div>
    </div>
    @endif

    {{-- Drop Zone --}}
    <label class="gd-drop-zone" :class="{ 'gd-drop-active': uploading }">
        <input type="file" accept="video/*" multiple class="sr-only" @change="handleFileSelect($event)">
        <div class="gd-drop-icon">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
        </div>
        <p class="gd-drop-text">
            <span class="gd-drop-browse">Pilih video</span> atau drag & drop di sini
        </p>
        <p class="gd-drop-hint">MP4, MOV, AVI, WEBM — Upload langsung ke Cloudinary, tidak ada batasan ukuran</p>
    </label>

    {{-- Progress Bar --}}
    <div class="gd-upload-progress" x-show="uploading" x-cloak>
        <div class="gd-progress-label">
            <svg width="14" height="14" class="gd-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Mengupload ke Cloudinary... <span x-text="uploadProgress + '%'"></span>
        </div>
        <div class="gd-progress-bar">
            <div class="gd-progress-fill" :style="'width:' + uploadProgress + '%'"></div>
        </div>
    </div>

    {{-- Daftar Video yang Sudah Diupload --}}
    <template x-if="videos.length > 0">
        <div class="gd-video-list">
            <p class="gd-video-list-label">Video berhasil diupload:</p>
            <template x-for="(v, i) in videos" :key="i">
                <div class="gd-video-list-item">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="gd-video-list-name" x-text="v.name"></span>
                    <span class="gd-video-list-url" x-text="v.url"></span>
                    <button type="button" class="gd-video-list-remove" @click="removeVideo(i)">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
            <p class="gd-video-list-note">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                URL video di atas akan disimpan otomatis bersama galeri ini saat klik Simpan.
            </p>
        </div>
    </template>

    {{-- Hidden input untuk kirim URL video ke Livewire --}}
    <input type="hidden" name="cloudinary_videos" x-bind:value="JSON.stringify(videos)">
</div>

<style>
.gd-video-upload-wrap { padding: 4px 0; }
.gd-upload-warning {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    background: rgba(245,158,11,0.1);
    border: 1px solid rgba(245,158,11,0.3);
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 16px;
    color: #92400e;
    font-size: 0.82rem;
    line-height: 1.5;
}
.gd-drop-zone {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 2px dashed rgba(100,116,139,0.4);
    border-radius: 10px;
    padding: 32px 20px;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
}
.gd-drop-zone:hover, .gd-drop-active {
    border-color: rgba(99,102,241,0.6);
    background: rgba(99,102,241,0.04);
}
.gd-drop-icon { color: #64748b; }
.gd-drop-text { font-size: 0.9rem; color: #64748b; margin: 0; text-align: center; }
.gd-drop-browse { color: #6366f1; font-weight: 600; }
.gd-drop-hint { font-size: 0.75rem; color: #94a3b8; margin: 0; text-align: center; }
.gd-upload-progress { margin-top: 12px; }
.gd-progress-label { display: flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #64748b; margin-bottom: 6px; }
.gd-spin { animation: gdSpin 1s linear infinite; }
@keyframes gdSpin { to { transform: rotate(360deg); } }
.gd-progress-bar { height: 4px; background: rgba(100,116,139,0.2); border-radius: 2px; overflow: hidden; }
.gd-progress-fill { height: 100%; background: #6366f1; border-radius: 2px; transition: width 0.2s; }
.gd-video-list { margin-top: 16px; }
.gd-video-list-label { font-size: 0.75rem; font-weight: 700; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 8px; }
.gd-video-list-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: rgba(99,102,241,0.06);
    border: 1px solid rgba(99,102,241,0.2);
    border-radius: 8px;
    margin-bottom: 6px;
    font-size: 0.82rem;
}
.gd-video-list-item svg { color: #6366f1; flex-shrink: 0; }
.gd-video-list-name { font-weight: 600; color: #e2e8f0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
.gd-video-list-url { font-size: 0.72rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; }
.gd-video-list-remove { margin-left: auto; flex-shrink: 0; background: none; border: none; cursor: pointer; color: #94a3b8; padding: 2px; border-radius: 4px; display: flex; }
.gd-video-list-remove:hover { color: #f87171; }
.gd-video-list-note { font-size: 0.75rem; color: #475569; display: flex; align-items: center; gap: 5px; margin: 6px 0 0; }
</style>
