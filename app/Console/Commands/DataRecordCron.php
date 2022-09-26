<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DataRecordCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This reads random data from the DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $randomInt  = rand(23, 2081);
        $order = DB::table('orders')->where('id', $randomInt)->first();
        \Log::info("Random Int: " . $randomInt . ", Order ID: " . $order->id . ", schedule_id: " . $order->schedule_id . ", created_at" . $order->created_at);
        return 0;
    }
}
