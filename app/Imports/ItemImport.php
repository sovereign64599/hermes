<?php

namespace App\Imports;

use App\Models\Items;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
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
            $name = empty($row['name']) ? 'Item ' . bin2hex(random_bytes(2)) : $row['name'];
            $total_cost = ((int)str_replace(',', '', empty($row['cost']) ? 0: $row['cost']) * $qty);
            return new Items([
                'item_name' => $name,
                'item_category' => $row['category'],
                'item_sub_category' => $row['subcategory'],
                'item_quantity' => $qty,
                'item_barcode' => $row['barcode'],
                'item_description' => $row['description'],
                'item_cost' => empty($row['cost']) ? 0 : str_replace(',', '', $row['cost']),
                'item_sell' => empty($row['sell']) ? 0 : str_replace(',', '', $row['sell']),
                'item_notes' => $row['notes'],
                'item_photo' => $row['photo'],
                'total_cost' => $total_cost
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }

    // public function chunkSize(): int
    // {
    //     return 1000;
    // }
}
