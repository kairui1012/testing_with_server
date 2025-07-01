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
        // Drop table if it exists and recreate
        Schema::dropIfExists('feedbacks');
        
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('phone');                    // User phone number
            $table->text('form_description')->nullable(); // Form description
            $table->text('good')->nullable();          // Good feedback
            $table->text('bad')->nullable();           // Bad feedback
            $table->text('remark')->nullable();        // Additional remarks
            $table->text('reference')->nullable();     // Reference (legacy)
            $table->string('referrer')->nullable();    // Referrer information
            $table->string('week');                     // Week identifier (e.g., 2025-W02)
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('phone');
            $table->index('week');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
