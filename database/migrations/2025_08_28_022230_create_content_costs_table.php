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
        Schema::create('content_costs', function (Blueprint $table) {
            $table->id();
            // Setiap entri biaya terhubung ke satu artikel
            $table->foreignId('article_id')->constrained('content_articles')->cascadeOnDelete();
            
            // Kolom-kolom biaya sesuai blueprint
            $table->unsignedInteger('time_sec')->default(0); // Waktu dalam detik
            $table->decimal('rate_per_hour', 15, 2)->default(0);
            $table->decimal('tools_cost', 15, 2)->default(0);
            $table->decimal('image_cost', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_costs');
    }
};
