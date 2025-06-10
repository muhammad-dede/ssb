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
        Schema::create('coach', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['MALE', 'FEMALE']);
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('national_id_number')->nullable();
            $table->string('photo')->nullable();
            // Coaching License
            $table->string('coaching_license');
            $table->string('license_number')->nullable();
            $table->date('license_issued_at')->nullable();
            $table->date('license_expired_at')->nullable();
            $table->string('license_issuer')->nullable();
            $table->string('status')->nullable()->default('INACTIVE');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach');
    }
};
