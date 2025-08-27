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
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();

            // Setiap KPI harus terhubung ke satu performance review
            $table->foreignId('performance_review_id')->constrained('performance_reviews')->cascadeOnDelete();
            
            $table->text('description'); // Deskripsi KPI, cth: "Menerbitkan artikel SEO"
            $table->string('target_metric'); // Satuan target, cth: "Artikel", "%", "Rupiah"
            $table->decimal('target_value', 15, 2); // Nilai target, cth: 15
            $table->decimal('actual_value', 15, 2)->nullable(); // Nilai aktual yang dicapai
            
            // Bobot KPI dalam persen (0-100)
            $table->unsignedTinyInteger('weight'); 

            // Skor untuk KPI ini (biasanya dihitung dari actual vs target)
            $table->decimal('score', 5, 2)->nullable(); 
            $table->text('manager_comment')->nullable(); // Komentar manajer spesifik untuk KPI ini

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpis');
    }
};
