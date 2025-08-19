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
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            
            // Periode Gaji
            $table->unsignedSmallInteger('month');
            $table->unsignedSmallInteger('year');
            $table->date('pay_date')->comment('Tanggal Pembayaran Gaji');

            // Snapshot komponen gaji saat itu
            $table->json('earnings')->comment('Semua komponen pendapatan dalam format JSON');
            $table->json('deductions')->comment('Semua komponen potongan dalam format JSON');

            // Total Kalkulasi
            $table->decimal('total_earnings', 15, 2);
            $table->decimal('total_deductions', 15, 2);
            $table->decimal('net_pay', 15, 2)->comment('Gaji Bersih (Take-Home Pay)');

            // Informasi Tambahan
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
