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
        Schema::create('billing', function (Blueprint $table) {
            $table->id();
            $table->morphs('billable'); // Polymorphic relationship
            $table->string('invoice')->unique();
            $table->double('amount')->default(0);
            $table->date('due_date')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->nullable()->default('UNPAID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing');
    }
};
