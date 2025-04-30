<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('classroom_code');
            $table->integer('time');
            $table->integer('smtr');  // 新增 smtr 欄位
            $table->timestamps();

            $table->foreign('classroom_code')
                ->references('code')
                ->on('classrooms')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
