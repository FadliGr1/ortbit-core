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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date');
            $table->time('check_in_at')->nullable();
            $table->time('check_out_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('present');

            // Kolom Baru untuk Penyesuaian
            $table->string('adjustment_status')->nullable()->comment('pending, approved, rejected');
            $table->time('adjusted_check_in_at')->nullable();
            $table->time('adjusted_check_out_at')->nullable();
            $table->text('adjustment_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
            $table->unique(['employee_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
