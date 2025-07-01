<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
       public function up()
   {
       if (!Schema::hasTable('daily_logs')) {
           Schema::create('daily_logs', function (Blueprint $table) {
               $table->bigIncrements('id');
               $table->unsignedBigInteger('user_id');
               $table->date('log_date');
               $table->boolean('open_enjoy_app')->default(false);
               $table->boolean('check_in')->default(false);
               $table->boolean('play_view_video')->default(false);
               $table->timestamps();
           });
       }
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_logs');
    }
};
