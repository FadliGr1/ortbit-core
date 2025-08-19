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
            // Menambahkan kolom untuk menghubungkan ke tabel departments
            // onDelete('set null') berarti jika departemen dihapus, kolom ini akan menjadi null, bukan menghapus data karyawan.
            $table->foreignId('department_id')->nullable()->after('position')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Ini adalah kebalikan dari 'up()', untuk menghapus kolom jika migration di-rollback.
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
