<?php

namespace App\Imports;

use App\Card;
use App\CardCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class CardsImport implements ToModel, WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Card([
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'cardCategory_id' => CardCategory::where('name', $row['cardcategory'])->first()->id,
        ]);
    }
}
