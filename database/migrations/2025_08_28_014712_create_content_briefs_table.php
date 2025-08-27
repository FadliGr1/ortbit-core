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
        Schema::create('content_briefs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            
            $table->string('keyword_primary');
            $table->string('intent')->nullable();
            $table->string('angle')->nullable();
            $table->text('outline_md')->nullable(); // Outline dalam format Markdown
            $table->unsignedInteger('wordcount_goal')->default(1000);
            $table->date('due_date')->nullable();

            $table->string('status')->default('brief'); // Contoh status: brief, in_progress, completed

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_briefs');
    }
};
