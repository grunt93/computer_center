<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 由於Laravel限制，無法直接修改ENUM，要用原生 SQL
        DB::statement("ALTER TABLE users CHANGE role role ENUM('admin', 'staff', 'super_admin') NOT NULL DEFAULT 'staff'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users CHANGE role role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff'");
    }
};
