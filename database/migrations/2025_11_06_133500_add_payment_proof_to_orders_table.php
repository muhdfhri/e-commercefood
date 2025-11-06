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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('payment_status');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_proof');
            $table->foreignId('verified_by')->nullable()->after('payment_verified_at')->constrained('users');
            $table->text('payment_notes')->nullable()->after('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['payment_proof', 'payment_verified_at', 'verified_by', 'payment_notes']);
        });
    }
};
