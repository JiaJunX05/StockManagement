<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Zone;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        foreach(range('A', 'E') as $letter) {
            Zone::create([
                // 'zone_name' => $letter,
                'zone_name' => 'Zone ' . $letter,
            ]);
        }
    }
}
