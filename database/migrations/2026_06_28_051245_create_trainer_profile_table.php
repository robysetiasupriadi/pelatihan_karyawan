<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainer_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('spesialisasi')->nullable();
            $table->text('bio')->nullable();
            $table->string('no_sertifikasi_trainer')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->integer('total_pelatihan_diampu')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainer_profile');
    }
};