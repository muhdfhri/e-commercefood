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
        Schema::table('products', function (Blueprint $table) {
            // For MariaDB compatibility, use raw SQL
            \DB::statement("ALTER TABLE products CHANGE size variant VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // For MariaDB compatibility, use raw SQL
            \DB::statement("ALTER TABLE products CHANGE variant size VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        });
    }
};
