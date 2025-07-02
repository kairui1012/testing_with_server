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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('phone');   // 推荐 string 适合手机号
            $table->text('good')->nullable();      // 用户填写的好评
            $table->text('bad')->nullable();       // 用户填写的差评
            $table->text('remark')->nullable();    // 用户备注
            $table->text('referrer')->nullable();    // 用户朋友
            $table->string('week');
            $table->timestamps();
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
