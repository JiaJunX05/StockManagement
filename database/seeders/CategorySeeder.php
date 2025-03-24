<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $categories = [
            ['category_name' => 'T-shirt'],
            ['category_name' => 'Shoes'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
