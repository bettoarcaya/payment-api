<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Payment extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'client_id',
        'payment_date',
        'expires_at',
        'status',
        'clp_usd'
    ];
    
    public function newUniqueId()
    {
        return (string) Uuid::uuid4();
    }
}
