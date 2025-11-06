<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateOrdersTableForPaymentMethods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tambahkan kolom payment_method_id
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_method_id')->nullable()->after('payment_method');
        });

        // Tambahkan foreign key constraint setelah kolom dibuat
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('payment_method_id')
                  ->references('id')
                  ->on('payment_methods')
                  ->onDelete('set null');
        });
        
        // Migrasi data dari payment_method (string) ke payment_method_id (foreign key)
        // Pastikan tabel payment_methods sudah ada dan berisi data
        if (Schema::hasTable('payment_methods')) {
            // Update data yang ada dengan nilai default untuk sementara
            DB::update("UPDATE orders SET payment_method = 'cod' WHERE payment_method IS NULL");
            
            // Update payment_method_id berdasarkan kode yang ada di payment_method
            DB::update("UPDATE orders o 
                       JOIN payment_methods pm ON o.payment_method = pm.code 
                       SET o.payment_method_id = pm.id");
        }
        
        // Hapus kolom payment_method yang lama
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Kembalikan kolom payment_method yang lama
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->after('payment_method_id')->nullable();
        });
        
        // Migrasi data kembali ke format lama
        if (Schema::hasTable('payment_methods')) {
            DB::update("UPDATE orders o 
                       JOIN payment_methods pm ON o.payment_method_id = pm.id 
                       SET o.payment_method = pm.code");
        }
        
        // Hapus foreign key constraint
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
        });
        
        // Hapus kolom payment_method_id
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_method_id');
        });
    }
};
