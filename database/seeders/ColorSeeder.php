<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Rouge', 'hex_code' => '#FF0000', 'is_active' => true],
            ['name' => 'Bleu', 'hex_code' => '#0000FF', 'is_active' => true],
            ['name' => 'Vert', 'hex_code' => '#00FF00', 'is_active' => true],
            ['name' => 'Noir', 'hex_code' => '#000000', 'is_active' => true],
            ['name' => 'Blanc', 'hex_code' => '#FFFFFF', 'is_active' => true],
            ['name' => 'Jaune', 'hex_code' => '#FFFF00', 'is_active' => true],
            ['name' => 'Orange', 'hex_code' => '#FFA500', 'is_active' => true],
            ['name' => 'Violet', 'hex_code' => '#800080', 'is_active' => true],
            ['name' => 'Rose', 'hex_code' => '#FFC0CB', 'is_active' => true],
            ['name' => 'Gris', 'hex_code' => '#808080', 'is_active' => true],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}
