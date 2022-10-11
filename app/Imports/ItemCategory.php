<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemCategory implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $checkDuplicate = Category::where('category_name', $row['category'])->first();
            if(empty($checkDuplicate)){
                $category = Category::create([
                    'category_name' => $row['category']
                ]);
            }
            $subCategory = SubCategory::where('category_name', $row['category'])->where('sub_category_name', $row['subcategory'])->first();
            if(empty($subCategory)){
                $category->subcategory()->create([
                    'category_name' => $row['category'],
                    'sub_category_name' => $row['subcategory']
                ]);
            }
        }
    }
}
