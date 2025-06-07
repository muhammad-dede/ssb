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
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('billing_id')->nullable()->index();
            $table->double('amount')->default(0);
            $table->date('payment_date')->nullable();
            $table->enum('method', ['TRANSFER', 'CASH']);
            // Informasi Transfer (jika metode = TRANSFER)
            $table->string('receiver_bank_code')->nullable()->index();
            $table->string('receiver_account_number')->nullable();
            $table->string('receiver_account_holder_name')->nullable();
            $table->string('sender_bank_code')->nullable()->index();
            $table->string('sender_account_number')->nullable();
            $table->string('sender_account_holder_name')->nullable();
            $table->string('proof_file')->nullable();
            $table->string('reference_number')->nullable();
            // Catatan Pembayaran
            $table->text('notes')->nullable();
            $table->string('status')->nullable()->default('PENDING');
            $table->timestamps();

            $table->foreign('billing_id')->references('id')->on('billing')->onDelete('cascade');
            $table->foreign('receiver_bank_code')->references('code')->on('bank')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('sender_bank_code')->references('code')->on('bank')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
