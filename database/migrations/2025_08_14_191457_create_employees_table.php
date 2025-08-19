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
        
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            // Informasi Pekerjaan
            $table->string('position');
            $table->date('join_date');
            $table->string('status')->default('active');

            // Data Pribadi (opsional)
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('nik')->nullable(); // Nomor Induk Kependudukan
            $table->string('npwp')->nullable(); // Nomor Pokok Wajib Pajak

            // Informasi Keuangan (dienkripsi)
            $table->text('bank_account_details')->nullable(); // Disimpan sebagai teks terenkripsi

            // Dokumen (disimpan sebagai path file)
            $table->string('ktp_path')->nullable();
            $table->string('npwp_path')->nullable();
            $table->string('contract_path')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
