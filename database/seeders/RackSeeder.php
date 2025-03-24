<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Rack;

class RackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        foreach(range(1, 5) as $number) {
            Rack::create([
                // 'rack_number' => $number,
                'rack_number' => 'Rack ' . $number,
            ]);
        }
    }
}
