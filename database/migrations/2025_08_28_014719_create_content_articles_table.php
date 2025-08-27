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
        Schema::create('content_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $table->foreignId('brief_id')->nullable()->constrained('content_briefs')->nullOnDelete();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('body')->nullable(); // Isi artikel utama
            
            $table->unsignedBigInteger('cms_post_id')->nullable(); // ID Post di WordPress/CMS lain

            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('editor_id')->nullable()->constrained('users');

            $table->timestamp('publish_date')->nullable();
            
            // Status alur kerja sesuai blueprint
            $table->string('status')->default('draft'); // idea → brief → draft → seo_check → review → ready → published → refresh_due → updated

            $table->json('seo_checklist')->nullable(); // Untuk menyimpan checklist SEO

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_articles');
    }
};
