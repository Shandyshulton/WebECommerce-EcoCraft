<?php

namespace Database\Seeders;

use App\Models\ImpactFactor;
use Illuminate\Database\Seeder;

class ImpactFactorSeeder extends Seeder
{
    public function run(): void
    {
        // Estimasi kasar (kg per item) — dapat disesuaikan admin.
        $materials = [
            ['material_type' => 'Bambu',            'waste_per_item' => 1.50, 'carbon_per_item' => 2.20],
            ['material_type' => 'Rotan',            'waste_per_item' => 1.30, 'carbon_per_item' => 2.00],
            ['material_type' => 'Kayu',             'waste_per_item' => 2.50, 'carbon_per_item' => 3.80],
            ['material_type' => 'Tanah Liat',       'waste_per_item' => 1.00, 'carbon_per_item' => 1.60],
            ['material_type' => 'Tenun',            'waste_per_item' => 0.80, 'carbon_per_item' => 1.40],
            ['material_type' => 'Anyaman Mendong',  'waste_per_item' => 0.90, 'carbon_per_item' => 1.30],
            ['material_type' => 'Plastik Daur Ulang','waste_per_item' => 2.00, 'carbon_per_item' => 3.20],
            ['material_type' => 'Kertas Daur Ulang','waste_per_item' => 0.60, 'carbon_per_item' => 0.90],
            ['material_type' => 'Logam',            'waste_per_item' => 3.00, 'carbon_per_item' => 4.50],
            ['material_type' => 'Kaca',             'waste_per_item' => 1.80, 'carbon_per_item' => 2.60],
        ];

        foreach ($materials as $m) {
            ImpactFactor::updateOrCreate(['material_type' => $m['material_type']], $m);
        }
    }
}
