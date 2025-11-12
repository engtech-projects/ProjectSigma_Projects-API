<?php

namespace Database\Seeders;

use App\Models\Uom;
use Illuminate\Database\Seeder;

class UomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $uoms = [
            'Meter' => 'm',
            'Kilometer' => 'km',
            'Centimeter' => 'cm',
            'Millimeter' => 'mm',
            'Foot' => 'ft',
            'Inch' => 'in',
            'Yard' => 'yd',
            'Kilogram' => 'kg',
            'Gram' => 'g',
            'Ton' => 't',
            'Pound' => 'lb',
            'Quintal' => 'q',
            'Liter' => 'L',
            'Milliliter' => 'mL',
            'Gallon' => 'gal',
            'Cubic Foot' => 'ft³',
            'Square Meter' => 'm²',
            'Square Kilometer' => 'km²',
            'Hectare' => 'ha',
            'Square Foot' => 'ft²',
            'Square Yard' => 'yd²',
            'Square Inch' => 'in²',
            'Newton' => 'N',
            'Kilonewton' => 'kN',
            'Pound-force' => 'lbf',
            'Cubic Meter' => 'm³',
            'Cubic Centimeter' => 'cm³',
            'Cubic Inch' => 'in³',
            'Piece' => 'pc',
            'linear meter' => 'lm',
            'assembly' => 'assy',
            'box' => 'box',
            'board feet' => 'bdft',
            'bottles' => 'btl',
            'can' => 'can',
            'case' => 'case',
            'dozen' => 'doz',
            'drum' => 'drum',
            'sheet' => 'sheet',
            'lot' => 'lot',
            'pack' => 'pack',
            'pail' => 'pail',
            'pad' => 'pad',
            'ream' => 'ream',
            'roll' => 'roll',
            'sack' => 'sack',
            'bag' => 'bag',
            'service' => 'service',
            'set' => 'set',
            'stab' => 'stab',
            'trip' => 'trip',
            'unit' => 'unit',
            'horsepower' => 'hp',
            'tonner bag' => 't-bag',
            'ounce' => 'oz',
            'milligram' => 'mg',
            'tank' => 'tank',
            'Bundle' => 'bundle',
            'Pairs' => 'pair',
        ];
        $counter = 0;
        foreach ($uoms as $name => $symbol) {
            Uom::upsert(
                ['id' => $counter, 'name' => ucfirst($name), 'symbol' => $symbol, 'description' => ucfirst($name)],
                ['id'],
                ['name', 'symbol', 'description']
            );
            $counter++;
        }
    }
}
