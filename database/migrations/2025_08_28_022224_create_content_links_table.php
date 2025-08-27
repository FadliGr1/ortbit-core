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
        Schema::create('content_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('content_articles')->cascadeOnDelete();
            $table->enum('type', ['internal', 'external']);
            $table->text('url');
            $table->string('anchor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_links');
    }
};
