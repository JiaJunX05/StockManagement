<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Zone;
use App\Models\Rack;
use App\Models\Storack;

class StorackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // 获取正确的 Zone 和 Rack
        $zones = Zone::whereIn('zone_name', array_map(fn($letter) => "Zone $letter", range('A', 'E')))->get();
        $racks = Rack::whereIn('rack_number', array_map(fn($number) => "Rack $number", range(1, 5)))->get();

        // 生成 Storack 记录
        foreach ($zones as $zone) {
            foreach ($racks as $rack) {
                Storack::create([
                    'zone_id' => $zone->id,
                    'rack_id' => $rack->id,
                ]);
            }
        }
    }
}
