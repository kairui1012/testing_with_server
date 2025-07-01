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
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->text('good')->nullable()->change();
            $table->text('bad')->nullable()->change();
            $table->text('remark')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->text('good')->nullable(false)->change();
            $table->text('bad')->nullable(false)->change();
            $table->text('remark')->nullable(false)->change();
        });
    }
};
