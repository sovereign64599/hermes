<?php

namespace App\Imports;

use App\Models\Items;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemImport implements ToModel, WithHeadingRow
{
    
    public function model(array $row)
    {
        $checkItem = Items::where('item_name', $row['name'])
                    ->where('item_category', $row['category'])
                    ->where('item_sub_category', $row['subcategory'])
                    ->where('item_barcode', $row['barcode'])->first();

        if(empty($checkItem)){
            $qty = empty($row['quantity']) ? 0 : $row['quantity'];
            return new Items([
                'item_name' => $row['name'],
                'item_category' => $row['category'],
                'item_sub_category' => $row['subcategory'],
                'item_quantity' => $qty,
                'item_barcode' => $row['barcode'],
                'item_description' => $row['description'],
                'item_cost' => str_replace(',', '', (float)$row['cost']),
                'item_sell' => str_replace(',', '', (float)$row['sell']),
                'item_notes' => $row['notes'],
                'item_photo' => $row['photo'],
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
