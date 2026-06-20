<?php

namespace Database\Seeders;

use App\Models\KategoriPelatihan;
use App\Models\Penilaian;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ──────────────────────────────────────────
        // USERS — firstOrCreate agar aman di-seed ulang
        // ──────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@pelatihan.test'],
            [
                'name'       => 'Administrator',
                'nip'        => 'ADM001',
                'department' => 'HRD',
                'position'   => 'HR Manager',
                'role'       => 'admin',
                'is_active'  => true,
                'password'   => Hash::make('password'),
            ]
        );

        $trainer = User::firstOrCreate(
            ['email' => 'trainer@pelatihan.test'],
            [
                'name'       => 'Budi Santoso',
                'nip'        => 'TRN001',
                'department' => 'IT',
                'position'   => 'Senior Developer',
                'role'       => 'trainer',
                'is_active'  => true,
                'password'   => Hash::make('password'),
            ]
        );

        $trainer2 = User::firstOrCreate(
            ['email' => 'trainer2@pelatihan.test'],
            [
                'name'       => 'Siti Rahayu',
                'nip'        => 'TRN002',
                'department' => 'Finance',
                'position'   => 'Finance Analyst',
                'role'       => 'trainer',
                'is_active'  => true,
                'password'   => Hash::make('password'),
            ]
        );

        $karyawan1 = User::firstOrCreate(
            ['email' => 'karyawan@pelatihan.test'],
            [
                'name'       => 'Andi Wijaya',
                'nip'        => 'KRY001',
                'department' => 'IT',
                'position'   => 'Junior Developer',
                'role'       => 'karyawan',
                'is_active'  => true,
                'password'   => Hash::make('password'),
            ]
        );

        $karyawan2 = User::firstOrCreate(
            ['email' => 'karyawan2@pelatihan.test'],
            [
                'name'       => 'Dewi Kusuma',
                'nip'        => 'KRY002',
                'department' => 'Marketing',
                'position'   => 'Marketing Staff',
                'role'       => 'karyawan',
                'is_active'  => true,
                'password'   => Hash::make('password'),
            ]
        );

        // ──────────────────────────────────────────
        // KATEGORI
        // ──────────────────────────────────────────
        $katIT  = KategoriPelatihan::firstOrCreate(
            ['nama' => 'Teknologi Informasi'],
            ['deskripsi' => 'Pelatihan bidang IT & pengembangan software']
        );
        $katHR  = KategoriPelatihan::firstOrCreate(
            ['nama' => 'Human Resources'],
            ['deskripsi' => 'Pelatihan SDM, kepemimpinan & manajemen']
        );
        $katFin = KategoriPelatihan::firstOrCreate(
            ['nama' => 'Keuangan'],
            ['deskripsi' => 'Pelatihan keuangan, akuntansi & perpajakan']
        );
        $katSoft = KategoriPelatihan::firstOrCreate(
            ['nama' => 'Soft Skills'],
            ['deskripsi' => 'Pelatihan komunikasi, presentasi & teamwork']
        );

        // ──────────────────────────────────────────
        // PELATIHAN
        // ──────────────────────────────────────────
        $pelatihan1 = Pelatihan::firstOrCreate(
            ['kode' => 'PLT-2026-0001'],
            [
                'nama'            => 'Laravel Framework untuk Pengembang Web',
                'deskripsi'       => 'Pelatihan intensif pengembangan aplikasi web modern menggunakan Laravel 12, mencakup routing, Eloquent ORM, API, dan deployment.',
                'kategori_id'     => $katIT->id,
                'tanggal_mulai'   => '2026-07-01',
                'tanggal_selesai' => '2026-07-05',
                'jam_mulai'       => '08:00',
                'jam_selesai'     => '17:00',
                'lokasi'          => 'Ruang Training Lt. 3',
                'kuota'           => 20,
                'metode'          => 'offline',
                'status'          => 'ongoing',
                'biaya'           => 0,
            ]
        );

        $pelatihan2 = Pelatihan::firstOrCreate(
            ['kode' => 'PLT-2026-0002'],
            [
                'nama'            => 'Leadership & Manajemen Tim',
                'deskripsi'       => 'Pelatihan kepemimpinan efektif, manajemen konflik, dan pengembangan tim berkinerja tinggi.',
                'kategori_id'     => $katHR->id,
                'tanggal_mulai'   => '2026-07-10',
                'tanggal_selesai' => '2026-07-11',
                'jam_mulai'       => '09:00',
                'jam_selesai'     => '16:00',
                'lokasi'          => 'Aula Gedung Utama',
                'kuota'           => 30,
                'metode'          => 'offline',
                'status'          => 'published',
                'biaya'           => 0,
            ]
        );

        $pelatihan3 = Pelatihan::firstOrCreate(
            ['kode' => 'PLT-2026-0003'],
            [
                'nama'            => 'Public Speaking & Presentasi Profesional',
                'deskripsi'       => 'Meningkatkan kemampuan berbicara di depan umum dan membuat presentasi yang menarik dan persuasif.',
                'kategori_id'     => $katSoft->id,
                'tanggal_mulai'   => '2026-08-01',
                'tanggal_selesai' => '2026-08-02',
                'jam_mulai'       => '08:00',
                'jam_selesai'     => '16:00',
                'lokasi'          => 'Zoom Meeting',
                'kuota'           => 50,
                'metode'          => 'online',
                'status'          => 'published',
                'biaya'           => 0,
            ]
        );

        // ──────────────────────────────────────────
        // TRAINERS (many-to-many: pelatihan_trainer)
        // syncWithoutDetaching agar aman di-seed ulang
        // ──────────────────────────────────────────
        $pelatihan1->trainers()->syncWithoutDetaching([
            $trainer->id => ['peran' => 'trainer'],
        ]);
        $pelatihan2->trainers()->syncWithoutDetaching([
            $trainer2->id => ['peran' => 'trainer'],
            $trainer->id  => ['peran' => 'co-trainer'],
        ]);
        $pelatihan3->trainers()->syncWithoutDetaching([
            $trainer2->id => ['peran' => 'trainer'],
        ]);

        // ──────────────────────────────────────────
        // PESERTA (many-to-many: pendaftaran)
        // sync() replace semua data pivot — pastikan selalu fresh
        // ──────────────────────────────────────────
        $pelatihan1->peserta()->sync([
            $karyawan1->id => [
                'status'           => 'disetujui',
                'tanggal_daftar'   => now()->subDays(10),
                'tanggal_diproses' => now()->subDays(9),
            ],
            $karyawan2->id => [
                'status'           => 'disetujui',
                'tanggal_daftar'   => now()->subDays(10),
                'tanggal_diproses' => now()->subDays(9),
            ],
        ]);

        $pelatihan2->peserta()->sync([
            $karyawan1->id => [
                'status'         => 'pending',
                'tanggal_daftar' => now()->subDays(2),
            ],
            $karyawan2->id => [
                'status'           => 'disetujui',
                'tanggal_daftar'   => now()->subDays(3),
                'tanggal_diproses' => now()->subDays(2),
            ],
        ]);

        $pelatihan3->peserta()->sync([
            $karyawan1->id => [
                'status'         => 'pending',
                'tanggal_daftar' => now()->subDay(),
            ],
        ]);

        // ──────────────────────────────────────────
        // PENILAIAN — buat data contoh untuk semua yang sudah disetujui
        // ──────────────────────────────────────────
        $disetujui = [
            // pelatihan1: Andi & Dewi disetujui
            ['pelatihan' => $pelatihan1, 'user' => $karyawan1, 'pre'=>63, 'post'=>83, 'tugas'=>76,  'hadir'=>100],
            ['pelatihan' => $pelatihan1, 'user' => $karyawan2, 'pre'=>70, 'post'=>84, 'tugas'=>86,  'hadir'=>100],
            // pelatihan2: hanya Dewi disetujui
            ['pelatihan' => $pelatihan2, 'user' => $karyawan2, 'pre'=>65, 'post'=>78, 'tugas'=>80,  'hadir'=>100],
        ];

        foreach ($disetujui as $d) {
            $p = \App\Models\Penilaian::firstOrCreate(
                ['pelatihan_id' => $d['pelatihan']->id, 'user_id' => $d['user']->id],
                [
                    'nilai_pre_test'   => $d['pre'],
                    'nilai_post_test'  => $d['post'],
                    'nilai_tugas'      => $d['tugas'],
                    'nilai_kehadiran'  => $d['hadir'],
                    'status_kelulusan' => 'belum_dinilai',
                ]
            );
            $p->hitungNilaiAkhir();
            $p->save();
        }
    }
}
