<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disk_replacements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('classroom_code');
            $table->integer('smtr'); 
            $table->text('issue')->nullable();
            $table->timestamp('replaced_at')->useCurrent();

            $table->foreign('classroom_code')
                ->references('code')
                ->on('classrooms')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disk_replacements');
    }
};
