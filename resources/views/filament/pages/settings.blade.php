<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <form wire:submit="save">
        {{ $this->form }}

        <div style="margin-top: 2.5rem; margin-bottom: 1.5rem; display: flex; justify-content: flex-end;">
            <button type="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-75 cursor-not-allowed"
                style="display:inline-flex; align-items:center; gap:0.5rem; padding: 0.65rem 1.4rem; background: #2563eb; color: white; font-weight: 600; font-size: 0.95rem; border: none; border-radius: 0.5rem; cursor: pointer; transition: background 0.2s;"
                onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">

                {{-- Icon saat loading --}}
                <svg wire:loading wire:target="save" class="animate-spin" style="width:1rem;height:1rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>

                {{-- Icon floppy disk --}}
                <x-fas-floppy-disk wire:loading.remove wire:target="save" style="width:1rem;height:1rem;"/>

                {{-- Teks tombol --}}
                <span wire:loading.remove wire:target="save">Simpan</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
    </form>
</x-filament-panels::page>
