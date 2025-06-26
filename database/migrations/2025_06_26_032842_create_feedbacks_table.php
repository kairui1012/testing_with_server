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
            $table->string('week')->nullable();
            $table->timestamps(); // Laravel 默认 created_at 和 updated_at
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
