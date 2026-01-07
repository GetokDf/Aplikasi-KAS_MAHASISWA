<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        // Cek dulu, kalau tabel users belum ada, buat dulu
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->enum('role', ['admin', 'bendahara', 'mahasiswa'])->default('mahasiswa');
                $table->rememberToken();
                $table->timestamps();
            });
        } else {
            // Kalau sudah ada, tambah kolom role aja
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'role')) {
                    $table->enum('role', ['admin', 'bendahara', 'mahasiswa'])->default('mahasiswa')->after('password');
                }
            });
        }

        // Tabel Jabatan
        Schema::create('jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tabel Mahasiswa
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->string('nama');
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatan')->onDelete('set null');
            $table->string('email')->unique();
            $table->string('no_hp')->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();
        });

        // Tabel Uang Kas
        Schema::create('uang_kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal_bayar');
            $table->string('bulan');
            $table->integer('tahun');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        // Tabel Pengeluaran
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengeluaran');
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->string('bukti')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        // Tabel Saldo Kas
        Schema::create('saldo_kas', function (Blueprint $table) {
            $table->id();
            $table->decimal('saldo', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uang_kas');
        Schema::dropIfExists('pengeluaran');
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('jabatan');
        Schema::dropIfExists('saldo_kas');
    }
};
