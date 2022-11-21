<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $response = Client::all()->toArray();
        $clients = [];
        foreach($response as $res) {
            array_push($clients, [
                'id'        => $res['id'],
                'email'     => $res['email'],
                'join_date' => $res['created_at']
            ]);
        }

        return response()->json($clients);
    }
}
