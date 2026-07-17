<?php

namespace App\Filament\Pages;

use App\Models\SiteSettings;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

class Settings extends Page
{
    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan';
    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';
    protected static ?string $title           = 'Pengaturan Sasana';
    protected static ?int    $navigationSort  = 99;

    public function getView(): string
    {
        return 'filament.pages.settings';
    }

    public array $data = [];

    // Tidak ada lagi auto-count di Settings, cukup di beranda
    public function mount(): void
    {
        $this->data = [
            'nama_sasana'         => SiteSettings::get('nama_sasana'),
            'tagline'             => SiteSettings::get('tagline'),
            'deskripsi'           => SiteSettings::get('deskripsi'),
            'tahun_berdiri'       => SiteSettings::get('tahun_berdiri'),
            'foto_beranda'        => SiteSettings::get('foto_beranda'),
            'foto_tentang'        => json_decode(SiteSettings::get('foto_tentang', '[]'), true) ?? [],
            'hero_badge'          => SiteSettings::get('hero_badge'),
            'hero_judul'          => SiteSettings::get('hero_judul'),
            'hero_desc'           => SiteSettings::get('hero_desc'),
            'whatsapp'            => SiteSettings::get('whatsapp'),
            'instagram'           => SiteSettings::get('instagram'),
            'tiktok'              => SiteSettings::get('tiktok'),
            'facebook'            => SiteSettings::get('facebook'),
            'nama_tempat_latihan' => SiteSettings::get('nama_tempat_latihan'),
            'maps_url'            => SiteSettings::get('maps_url'),
        ];

        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Profil Sasana')
                ->description('Ditampilkan di footer dan halaman Tentang Kami')
                ->icon('heroicon-o-building-storefront')
                ->schema([
                    TextInput::make('nama_sasana')
                        ->label('Nama Sasana')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('tahun_berdiri')
                        ->label('Tahun Berdiri')
                        ->maxLength(4)
                        ->placeholder('1998'),

                    Textarea::make('deskripsi')
                        ->label('Deskripsi Singkat')
                        ->rows(3)
                        ->helperText('Tampil di footer bawah nama sasana')
                        ->columnSpanFull(),

                    FileUpload::make('foto_beranda')
                        ->label('Foto Tentang (Beranda)')
                        ->helperText('1 foto — tampil di section Tentang Kami di halaman Beranda')
                        ->image()
                        ->disk('public')
                        ->directory('settings/beranda')
                        ->imagePreviewHeight('200')
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Foto Halaman Tentang Kami')
                ->description('Foto pertama tampil besar, sisanya tampil kecil di bawahnya. Maks 5 foto.')
                ->icon('heroicon-o-photo')
                ->schema([
                    FileUpload::make('foto_tentang')
                        ->label('Upload Foto')
                        ->multiple()
                        ->reorderable()
                        ->appendFiles()
                        ->maxFiles(5)
                        ->image()
                        ->disk('public')
                        ->directory('settings/tentang')
                        ->panelLayout('grid')
                        ->imagePreviewHeight('150')
                        ->columnSpanFull(),
                ]),

            Section::make('Hero & Statistik')
                ->description('Teks dan angka yang tampil di section hero dan tentang di halaman Beranda')
                ->icon('heroicon-o-chart-bar')
                ->schema([
                    TextInput::make('hero_badge')
                        ->label('Teks Badge Hero')
                        ->placeholder('Pusat Latihan Tinju Terbaik Sejak 1998')
                        ->columnSpanFull(),

                    TextInput::make('hero_judul')
                        ->label('Judul Hero')
                        ->placeholder('Melatih Atlet, Mencetak Juara, Harum Bangsa!')
                        ->columnSpanFull(),

                    Textarea::make('hero_desc')
                        ->label('Deskripsi Hero')
                        ->rows(2)
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Tempat Latihan')
                ->description('Ditampilkan di halaman Kontak (kartu Lokasi Sasana) dan footer. Perubahan di sini otomatis berlaku di kedua tempat.')
                ->icon('heroicon-o-map-pin')
                ->schema([
                    TextInput::make('nama_tempat_latihan')
                        ->label('Nama Tempat Latihan')
                        ->placeholder('GOR Padjadjaran, Kota Bandung, Jawa Barat')
                        ->helperText('Nama lokasi yang tampil di kartu kontak dan footer')
                        ->columnSpanFull(),

                    TextInput::make('maps_url')
                        ->label('Link Google Maps')
                        ->placeholder('https://maps.google.com/?q=GOR+Padjadjaran+Bandung')
                        ->helperText('URL Google Maps — pengunjung diarahkan ke sini saat klik teks lokasi. Buka Google Maps → cari lokasi → salin link dari address bar.')
                        ->url()
                        ->columnSpanFull(),
                ])->columns(1),

            Section::make('Media Sosial')
                ->description('Ditampilkan sebagai ikon di footer')
                ->icon('heroicon-o-share')
                ->schema([
                    TextInput::make('instagram')
                        ->label('Instagram')
                        ->placeholder('https://instagram.com/syifaboxing'),

                    TextInput::make('tiktok')
                        ->label('TikTok')
                        ->placeholder('https://tiktok.com/@syifaboxing'),

                    TextInput::make('whatsapp')
                        ->label('WhatsApp')
                        ->placeholder('6281234567890')
                        ->helperText('Format: kode negara tanpa + (contoh: 6281234567890)'),

                    TextInput::make('facebook')
                        ->label('Facebook')
                        ->placeholder('https://facebook.com/syifaboxing'),
                ])->columns(2),

        ])->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            if ($key === 'foto_tentang') {
                SiteSettings::set($key, json_encode(array_values($value ?? [])));
            } else {
                SiteSettings::set($key, $value ?? '');
            }
        }

        SiteSettings::clearCache();

        Notification::make()
            ->title('Pengaturan berhasil disimpan!')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
