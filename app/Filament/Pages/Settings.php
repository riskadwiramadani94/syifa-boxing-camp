<?php

namespace App\Filament\Pages;

use App\Models\Galeri;
use App\Models\PendaftaranMember;
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

    // Dihitung otomatis dari database, tidak bisa diedit manual
    public int $autoMemberAktif = 0;
    public int $autoPrestasi    = 0;
    public int $autoMedali      = 0;

    public function mount(): void
    {
        // Hitung otomatis dari DB
        $this->autoMemberAktif = PendaftaranMember::where('aktif', true)->count();
        $this->autoPrestasi    = Galeri::where('kategori', 'pertandingan')->count();
        $this->autoMedali      = Galeri::where('kategori', 'pertandingan')->whereNotNull('juara')->count();

        $this->data = [
            'nama_sasana'   => SiteSettings::get('nama_sasana'),
            'tagline'       => SiteSettings::get('tagline'),
            'deskripsi'     => SiteSettings::get('deskripsi'),
            'tahun_berdiri' => SiteSettings::get('tahun_berdiri'),
            'foto_beranda'  => SiteSettings::get('foto_beranda'),
            'foto_tentang'  => json_decode(SiteSettings::get('foto_tentang', '[]'), true) ?? [],
            'hero_badge'    => SiteSettings::get('hero_badge'),
            'hero_judul'    => SiteSettings::get('hero_judul'),
            'hero_desc'     => SiteSettings::get('hero_desc'),
            'stat_tahun'    => SiteSettings::get('stat_tahun'),
            'whatsapp'      => SiteSettings::get('whatsapp'),
            'email'         => SiteSettings::get('email'),
            'alamat'        => SiteSettings::get('alamat'),
            'maps_embed'    => SiteSettings::get('maps_embed'),
            'instagram'          => SiteSettings::get('instagram'),
            'tiktok'             => SiteSettings::get('tiktok'),
            'facebook'           => SiteSettings::get('facebook'),
            'nama_tempat_latihan' => SiteSettings::get('nama_tempat_latihan'),
            'maps_url'           => SiteSettings::get('maps_url'),
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

                    \Filament\Forms\Components\Placeholder::make('stat_member_auto')
                        ->label('Member Aktif')
                        ->content(fn () => $this->autoMemberAktif . ' anggota')
                        ->helperText('Dihitung otomatis dari anggota dengan status Aktif = ON'),

                    TextInput::make('stat_tahun')
                        ->label('Tahun Berdiri')
                        ->placeholder('25+'),

                    \Filament\Forms\Components\Placeholder::make('stat_prestasi_auto')
                        ->label('Prestasi')
                        ->content(fn () => $this->autoPrestasi . ' item')
                        ->helperText('Dihitung otomatis dari Galeri kategori Prestasi'),

                    \Filament\Forms\Components\Placeholder::make('stat_medali_auto')
                        ->label('Medali')
                        ->content(fn () => $this->autoMedali . ' medali')
                        ->helperText('Dihitung otomatis dari Galeri kategori Pertandingan yang memiliki juara'),
                ])->columns(2),

            Section::make('Info Kontak')
                ->description('Ditampilkan di footer kolom kanan dan halaman Kontak')
                ->icon('heroicon-o-phone')                ->schema([
                    TextInput::make('email')
                        ->label('Email Resmi')
                        ->placeholder('info@syifaboxingcamp.com'),

                    Textarea::make('alamat')
                        ->label('Alamat Lengkap')
                        ->rows(2)
                        ->helperText('Tampil di footer dan kartu kontak')
                        ->columnSpanFull(),

                    Textarea::make('maps_embed')
                        ->label('Google Maps Embed URL')
                        ->rows(3)
                        ->placeholder('https://www.google.com/maps/embed?...')
                        ->helperText('Salin dari Google Maps → Bagikan → Sematkan peta → ambil nilai src="..."')
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

        // Update stat_member dan stat_prestasi otomatis dari DB
        SiteSettings::set('stat_member', (string) PendaftaranMember::where('aktif', true)->count());
        SiteSettings::set('stat_prestasi', (string) Galeri::where('kategori', 'prestasi')->count());

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
