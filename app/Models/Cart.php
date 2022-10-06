<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    use Uuid;

    protected $table = 'carts';
    protected $fillable = [
        'item_id',
        'cart_name',
        'cart_category',
        'cart_sub_category',
        'cart_quantity',
        'cart_name',
        'cart_barcode',
        'cart_description',
        'cart_cost',
        'cart_sell',
        'cart_notes',
        'cart_photo'
    ];
}
