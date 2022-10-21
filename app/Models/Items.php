<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;
    use Uuid;

    protected $table = 'items';
    protected $fillable = [
        'item_name',
        'item_category',
        'item_sub_category',
        'item_quantity',
        'item_barcode',
        'item_description',
        'item_cost',
        'item_sell',
        'item_notes',
        'item_photo',
        'total_cost',
    ];
}
