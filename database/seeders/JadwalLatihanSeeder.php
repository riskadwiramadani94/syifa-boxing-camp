<?php

namespace Database\Seeders;

use App\Models\JadwalLatihan;
use Illuminate\Database\Seeder;

class JadwalLatihanSeeder extends Seeder
{
    public function run(): void
    {
        JadwalLatihan::truncate();

        $jadwals = [
            ['hari' => 'Senin',  'kelas' => 'Teknik',   'jam_mulai' => '15:30', 'jam_selesai' => '17:30', 'pelatih' => 'Semua Level',            'aktif' => true],
            ['hari' => 'Selasa', 'kelas' => 'Sparring',  'jam_mulai' => '15:00', 'jam_selesai' => '17:00', 'pelatih' => 'Intermediate - Advanced', 'aktif' => true],
            ['hari' => 'Rabu',   'kelas' => 'Teknik',   'jam_mulai' => '15:30', 'jam_selesai' => '17:30', 'pelatih' => 'Semua Level',            'aktif' => true],
            ['hari' => 'Kamis',  'kelas' => 'Teknik',   'jam_mulai' => '15:30', 'jam_selesai' => '17:30', 'pelatih' => 'Semua Level',            'aktif' => true],
            ['hari' => 'Jumat',  'kelas' => 'Sparring',  'jam_mulai' => '15:00', 'jam_selesai' => '17:00', 'pelatih' => 'Intermediate - Advanced', 'aktif' => true],
            ['hari' => 'Sabtu',  'kelas' => 'Interval', 'jam_mulai' => '07:30', 'jam_selesai' => '09:30', 'pelatih' => 'Semua Level',            'aktif' => true],
            ['hari' => 'Minggu', 'kelas' => 'Rest',     'jam_mulai' => '00:00', 'jam_selesai' => '00:00', 'pelatih' => null, 'keterangan' => 'Istirahat', 'aktif' => true],
        ];

        foreach ($jadwals as $jadwal) {
            JadwalLatihan::create($jadwal);
        }
    }
}
