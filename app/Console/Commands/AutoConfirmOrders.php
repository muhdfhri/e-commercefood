<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class AutoConfirmOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-confirm {--days=7 : The number of days after which an order is auto-confirmed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically confirms orders that have been in "process/dikirim" status for too long';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $threshold = Carbon::now()->subDays($days);

        $orders = Order::where('status', 'process')
                       ->where('updated_at', '<=', $threshold)
                       ->get();

        $count = 0;
        foreach ($orders as $order) {
            $order->status = 'delivered';
            if ($order->payment_status == 'unpaid') {
                $order->payment_status = 'paid';
            }
            $order->save();
            
            // Optionally, create a system log in order_confirmations
            \App\Models\OrderConfirmation::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'photo_path' => 'auto-confirmed-by-system',
                'notes' => 'Otomatis diselesaikan oleh sistem setelah ' . $days . ' hari.'
            ]);

            $count++;
        }

        $this->info("Successfully auto-confirmed {$count} orders.");
    }
}
