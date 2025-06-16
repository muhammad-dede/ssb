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
        Schema::create('student_match_event_assessment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_match_event_id')->nullable()->index();
            $table->string('assessment_code')->nullable()->index();
            $table->double('value')->nullable();
            $table->timestamps();

            $table->foreign('student_match_event_id')->references('id')->on('student_match_event')->onDelete('cascade');
            $table->foreign('assessment_code')->references('code')->on('assessment')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_match_event_assessment');
    }
};
