<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PaymentProcess;
use Illuminate\Support\Facades\Http;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::where('client_id', $request->client)->get();
        $response = [];
        foreach($payments as $payment) {
            array_push($response, [
                "uuid"         => $payment['id'],
                "payment_date" => $payment['payment_date'],
                "expires_at"   => $payment['expires_at'],
                "status"       => $payment['status'],
                "client_id"    => $payment['client_id'],
                "clp_usd"      => $payment['clp_usd'],
            ]);
        }

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $response = PaymentProcess::dispatch()[0];
        return response()->json([
            "uuid"         => $response->id,
            "payment_date" => $response->payment_date,
            "expires_at"   => $response->expires_at,
            "status"       => $response->status,
            "client_id"    => $response->client_id,
            "clp_usd"      => $response->clp_usd,
        ]);
    }
}
