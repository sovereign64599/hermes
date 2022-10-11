<?php

namespace App\Imports;

use App\Models\Items;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $items = Items::firstOrCreate(
            [
                'item_name' => $row['item_name'],
                'item_category' => $row['item_category'],
                'item_sub_category' => $row['item_sub_category'],
                'item_barcode' => $row['item_barcode'],
            ],
            [
                'item_name' => $row['item_name'],
                'item_category' => $row['item_category'],
                'item_sub_category' => $row['item_sub_category'],
                'item_quantity' => $qty,
                'item_barcode' => $row['item_barcode'],
                'item_description' => $row['item_description'],
                'item_cost' => (float) str_replace(',', '', $row['item_cost']),
                'item_sell' => (float) str_replace(',', '', $row['item_sell']),
                'item_notes' => $row['item_notes']
            ]

        );

        if (! $items->wasRecentlyCreated) {
            $items->update([
                'item_name' => $row['item_name'],
                'item_category' => $row['item_category'],
                'item_sub_category' => $row['item_sub_category'],
                'item_quantity' => $qty,
                'item_barcode' => $row['item_barcode'],
                'item_description' => $row['item_description'],
                'item_cost' => (float) str_replace(',', '', $row['item_cost']),
                'item_sell' => (float) str_replace(',', '', $row['item_sell']),
                'item_notes' => $row['item_notes']
            ]);
        }

        // $checkItem = Items::where('item_name', '=', $row['item_name'])
        //             ->where('item_category', '=', $row['item_category'])
        //             ->where('item_sub_category', '=', $row['item_sub_category'])
        //             ->where('item_barcode', '=', $row['item_barcode'])->first();

        // if(empty($checkItem)){
        //     $qty = empty($row['item_quantity']) ? 0 : $row['item_quantity'];
        //     return new Items([
        //         'item_name' => $row['item_name'],
        //         'item_category' => $row['item_category'],
        //         'item_sub_category' => $row['item_sub_category'],
        //         'item_quantity' => $qty,
        //         'item_barcode' => $row['item_barcode'],
        //         'item_description' => $row['item_description'],
        //         'item_cost' => (float) str_replace(',', '', $row['item_cost']),
        //         'item_sell' => (float) str_replace(',', '', $row['item_sell']),
        //         'item_notes' => $row['item_notes']
        //     ]);
        // }
    }
}
