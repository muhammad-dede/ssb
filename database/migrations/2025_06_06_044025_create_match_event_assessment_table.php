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
        Schema::create('match_event_assessment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_event_id')->nullable()->index();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('assessment_code')->nullable()->index();
            $table->double('value')->nullable();
            $table->timestamps();

            $table->foreign('match_event_id')->references('id')->on('match_event')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('student')->onDelete('cascade');
            $table->foreign('assessment_code')->references('code')->on('assessment')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_event_assessment');
    }
};
