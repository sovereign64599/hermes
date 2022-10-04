<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string'; 
    protected $table = 'sub_categories';
    protected $fillable = [
        'category_id',
        'category_name',
        'sub_category_name',
    ];

    function category(){
        return $this->belongsTo(Category::class, 'id');
    }
}
