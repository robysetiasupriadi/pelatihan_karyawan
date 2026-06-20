<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategori pelatihan
        Schema::create('kategori_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Program pelatihan
        Schema::create('pelatihan', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->foreignId('kategori_id')->constrained('kategori_pelatihan')->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('lokasi')->nullable();
            $table->integer('kuota')->default(0);
            $table->enum('metode', ['online', 'offline', 'hybrid'])->default('offline');
            $table->string('link_meeting')->nullable();
            $table->enum('status', ['draft', 'published', 'ongoing', 'selesai', 'dibatalkan'])->default('draft');
            $table->string('cover')->nullable();
            $table->decimal('biaya', 12, 2)->default(0);
            $table->timestamps();
        });

        // [MANY-TO-MANY] Pivot: Pelatihan ↔ Trainer (User)
        // Satu pelatihan bisa punya banyak trainer, satu trainer bisa handle banyak pelatihan
        Schema::create('pelatihan_trainer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatihan_id')->constrained('pelatihan')->onDelete('cascade');
            $table->foreignId('trainer_id')->constrained('users')->onDelete('cascade');
            $table->string('peran')->default('trainer'); // trainer, co-trainer, fasilitator
            $table->unique(['pelatihan_id', 'trainer_id']);
            $table->timestamps();
        });

        // Materi / modul pelatihan
        Schema::create('materi_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatihan_id')->constrained('pelatihan')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file')->nullable();
            $table->string('link_video')->nullable();
            $table->integer('urutan')->default(0);
            $table->integer('durasi_menit')->nullable();
            $table->timestamps();
        });

        // [MANY-TO-MANY] Pendaftaran peserta (pivot: User ↔ Pelatihan)
        // Satu karyawan bisa ikut banyak pelatihan, satu pelatihan punya banyak peserta
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatihan_id')->constrained('pelatihan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'selesai'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_daftar')->useCurrent();
            $table->timestamp('tanggal_diproses')->nullable();
            $table->unique(['pelatihan_id', 'user_id']);
            $table->timestamps();
        });

        // [MANY-TO-MANY] Progress materi per peserta (pivot: User ↔ MateriPelatihan)
        // Satu peserta bisa punya progress di banyak materi, satu materi dicapai banyak peserta
        Schema::create('materi_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained('materi_pelatihan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('selesai')->default(false);
            $table->integer('persentase')->default(0); // 0-100
            $table->timestamp('tanggal_selesai')->nullable();
            $table->unique(['materi_id', 'user_id']);
            $table->timestamps();
        });

        // Absensi
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatihan_id')->constrained('pelatihan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('alpha');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->text('keterangan')->nullable();
            $table->unique(['pelatihan_id', 'user_id', 'tanggal']);
            $table->timestamps();
        });

        // Penilaian / evaluasi
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatihan_id')->constrained('pelatihan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('nilai_pre_test', 5, 2)->nullable();
            $table->decimal('nilai_post_test', 5, 2)->nullable();
            $table->decimal('nilai_tugas', 5, 2)->nullable();
            $table->decimal('nilai_kehadiran', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('grade')->nullable(); // A, B, C, D
            $table->enum('status_kelulusan', ['lulus', 'tidak_lulus', 'belum_dinilai'])->default('belum_dinilai');
            $table->text('catatan')->nullable();
            $table->unique(['pelatihan_id', 'user_id']);
            $table->timestamps();
        });

        // Sertifikat
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->foreignId('pelatihan_id')->constrained('pelatihan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_terbit');
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->string('file')->nullable();
            $table->unique(['pelatihan_id', 'user_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat');
        Schema::dropIfExists('penilaian');
        Schema::dropIfExists('absensi');
        Schema::dropIfExists('materi_progress');
        Schema::dropIfExists('pendaftaran');
        Schema::dropIfExists('pelatihan_trainer');
        Schema::dropIfExists('materi_pelatihan');
        Schema::dropIfExists('pelatihan');
        Schema::dropIfExists('kategori_pelatihan');
    }
};
