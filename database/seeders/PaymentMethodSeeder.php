<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = [
            [
                'name' => 'Bayar Ditempat (COD)',
                'code' => 'cod',
                'description' => 'Pembayaran dilakukan saat barang diterima',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Transfer Bank',
                'code' => 'bank_transfer',
                'description' => 'Transfer ke rekening bank yang tersedia',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'QRIS',
                'code' => 'qris',
                'description' => 'Pembayaran menggunakan QRIS',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Gunakan insert untuk menghindari mass assignment
        foreach (array_chunk($paymentMethods, 10) as $chunk) {
            PaymentMethod::insert($chunk);
        }
    }
}
