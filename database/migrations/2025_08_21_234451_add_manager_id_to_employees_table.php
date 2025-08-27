<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Menambahkan kolom manager_id setelah kolom user_id
            $table->foreignId('manager_id')
                  ->nullable() // Atasan bisa kosong (untuk level tertinggi seperti CEO)
                  ->after('user_id')
                  ->constrained('employees') // Foreign key ke tabel employees itu sendiri
                  ->onDelete('set null'); // Jika manajer dihapus, set manager_id menjadi null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['manager_id']);
            // Hapus kolomnya
            $table->dropColumn('manager_id');
        });
    }
};
