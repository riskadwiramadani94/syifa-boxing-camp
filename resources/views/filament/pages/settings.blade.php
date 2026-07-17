<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <form wire:submit="save">
        {{ $this->form }}

        <div style="margin-top: 2.5rem; margin-bottom: 1.5rem; display: flex; justify-content: flex-end;">
            <button type="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-75 cursor-not-allowed"
                style="display:inline-flex; align-items:center; gap:0.5rem; padding: 0.65rem 1.4rem; background: rgb(245 158 11); color: white; font-weight: 600; font-size: 0.95rem; border: none; border-radius: 0.5rem; cursor: pointer;">

                {{-- Icon saat loading --}}
                <i wire:loading wire:target="save"
                   class="fas fa-spinner fa-spin"
                   style="font-size: 1rem;"></i>

                {{-- Icon normal --}}
                <i wire:loading.remove wire:target="save"
                   class="fas fa-save"
                   style="font-size: 1rem;"></i>

                {{-- Teks tombol --}}
                <span wire:loading.remove wire:target="save">Simpan</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
    </form>
</x-filament-panels::page>
