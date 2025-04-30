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
        Schema::table('disk_replacements', function (Blueprint $table) {
            $table->boolean('disk_replaced')->default(false)->after('smtr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disk_replacements', function (Blueprint $table) {
            $table->dropColumn('disk_replaced');
        });
    }
};
