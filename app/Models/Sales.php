<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    use Uuid;

    protected $table = 'sales';
    protected $fillable = [
        'sales_amount',
        'transaction_number',
        'custom_date',
        'proccessed_by',
    ];
}
