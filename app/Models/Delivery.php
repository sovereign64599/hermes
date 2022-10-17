<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    use Uuid;

    protected $table = 'deliveries';
    protected $fillable = [
        'user_id',
        'user_name',
        'item_id',
        'item_name',
        'item_category',
        'item_sub_category',
        'item_quantity_deduct',
        'item_barcode',
        'item_description',
        'item_price',
        'total_amount',
        'form_number',
        'custom_date',
        'delivery_status'
    ];
}
