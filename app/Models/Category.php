<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    use Uuid;

    protected $table = 'categories';
    protected $fillable = [
        'category_name',
        'category_description',
    ];

    function subcategory(){
        return $this->hasMany(SubCategory::class);
    }
}
