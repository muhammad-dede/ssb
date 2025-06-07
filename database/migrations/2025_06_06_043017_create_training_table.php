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
        Schema::create('training', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('period_id')->nullable()->index();
            $table->string('program_code', 20)->nullable()->index();
            $table->unsignedBigInteger('coach_id')->nullable()->index();
            $table->date('training_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->nullable()->default('ACTIVE');
            $table->timestamps();

            $table->foreign('period_id')->references('id')->on('period')->onDelete('cascade');
            $table->foreign('program_code')->references('code')->on('program')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('coach_id')->references('id')->on('coach')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training');
    }
};
