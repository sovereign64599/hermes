<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferOutRecord extends Model
{
    use HasFactory;
    use Uuid;

    protected $table = 'transfer_out_records';
    protected $fillable = [
        'user_id',
        'user_name',
        'item_name',
        'item_category',
        'item_sub_category',
        'item_quantity',
        'item_quantity_deduct',
        'item_description',
        'item_barcode',
        'item_description',
        'item_cost',
        'item_sell',
        'item_photo',
        'form_number',
        'custom_date',
        'notes'
    ];
}
