<?php

namespace App\Listeners;

use App\Events\PaymentProcess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\ProcessDolarPrice;
use Illuminate\Support\Facades\Cache;
use App\Models\Payment;
use App\Models\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentProcessed;

class PaymentProcessListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PaymentProcess  $event
     */
    public function handle(PaymentProcess $event)
    {
        $data   = \Request::input();
        $client = Client::findOrFail($data['client_id']);

        $today_dolar_price = Cache::get('dolar_value');

        $data['status']  = 'pending';
        $data['clp_usd'] = $today_dolar_price ?? 0;

        $payment = Payment::create($data);

        //Dispatch only if the cache hasn't the dolar value for today yet
        ProcessDolarPrice::dispatchIf($today_dolar_price == null);

        //Sending the email using Queueing Mail feature
        Mail::to($client->email)->queue(new PaymentProcessed());

        return $payment;
    }
}
