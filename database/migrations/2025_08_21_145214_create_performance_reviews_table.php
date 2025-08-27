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
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke karyawan yang dinilai
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            
            // Relasi ke user (manajer/atasan) yang menilai
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();

            $table->string('title'); // Judul, cth: "Penilaian Kinerja Q4 2025"
            $table->date('period_start_date');
            $table->date('period_end_date');

            // Status dari proses review
            $table->enum('status', ['draft', 'in_progress', 'completed', 'cancelled'])->default('draft');

            $table->text('manager_feedback')->nullable();
            $table->text('employee_feedback')->nullable();

            // Skor akhir dari penilaian (dihitung dari semua KPI)
            $table->decimal('final_score', 5, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
