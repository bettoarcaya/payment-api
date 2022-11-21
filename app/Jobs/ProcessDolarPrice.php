<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Payment;

class ProcessDolarPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response    = Http::get('https://mindicador.cl/api/dolar');
        $dolar_price = $response->json($key = null)['serie'][0]['valor'];
        $dolar_date  = $response->json($key = null)['serie'][0]['fecha'];
        $to_seconds  = strtotime($dolar_date."+ 1 day");

        Cache::put('dolar_value', $dolar_price, $to_seconds);

        //Update the pending dolar values in today's payments
        Payment::Payment::where('clp_usd', '=', 0)
            ->where('payment_date', '>=', date("Y-m-d") . " 00:00:00")
            ->update(['clp_usd' => $dolar_price]);

    }
}
