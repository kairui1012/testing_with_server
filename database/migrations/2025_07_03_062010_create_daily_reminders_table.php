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
        Schema::create('daily_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('weekday'); // 1 for Monday, 2 for Tuesday, ..., 7 for Sunday
            $table->time('time');
            $table->text('message')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Ensure a user can only have one setting per weekday
            $table->unique(['user_id', 'weekday']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reminders');
    }
};
