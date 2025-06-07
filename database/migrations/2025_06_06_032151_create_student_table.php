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
        Schema::create('student', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['MALE', 'FEMALE']);
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('national_id_number')->nullable();
            $table->string('photo')->nullable();
            // Informasi Pemain
            $table->enum('dominant_foot', ['RIGHT', 'LEFT', 'BOTH']);
            $table->float('height_cm')->nullable();
            $table->float('weight_kg')->nullable();
            $table->string('status')->nullable()->default('ACTIVE');
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
        Schema::dropIfExists('student');
    }
};
