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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel employees
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            
            // --- KOMPONEN PENDAPATAN ---
            $table->decimal('base_salary', 15, 2)->default(0)->comment('Gaji Pokok');
            $table->decimal('transport_allowance', 15, 2)->default(0)->comment('Tunjangan Transportasi');
            $table->decimal('meal_allowance', 15, 2)->default(0)->comment('Tunjangan Makan');
            $table->json('other_allowances')->nullable()->comment('Tunjangan Lain (JSON)');

            // --- KOMPONEN POTONGAN ---
            $table->decimal('bpjs_health_deduction', 15, 2)->default(0)->comment('Potongan BPJS Kesehatan');
            $table->decimal('bpjs_employment_deduction', 15, 2)->default(0)->comment('Potongan BPJS Ketenagakerjaan');
            $table->decimal('tax_deduction', 15, 2)->default(0)->comment('Potongan Pajak (PPh 21)');
            $table->decimal('loan_deduction', 15, 2)->default(0)->comment('Potongan Pinjaman');
            $table->json('other_deductions')->nullable()->comment('Potongan Lain (JSON)');

            // Tanggal mulai berlakunya struktur gaji ini
            $table->date('effective_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
