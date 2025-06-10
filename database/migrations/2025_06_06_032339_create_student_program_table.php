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
        Schema::create('student_program', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('program_code', 20)->nullable()->index();
            $table->unsignedBigInteger('period_id')->nullable()->index();
            $table->string('status')->nullable()->default('INACTIVE');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('student')->onDelete('cascade');
            $table->foreign('program_code')->references('code')->on('program')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('period_id')->references('id')->on('period')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_program');
    }
};
