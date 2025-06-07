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
        Schema::create('bank_account', function (Blueprint $table) {
            $table->id();
            $table->string('bank_code', 4)->nullable()->index();
            $table->string('account_number')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('status')->nullable()->default('ACTIVE');
            $table->timestamps();

            $table->foreign('bank_code')->references('code')->on('bank')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_account');
    }
};
