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
            // 电子产品 & 配件
            ['category_name' => '电脑与配件'],
            ['category_name' => '相机与无人机'],

            // 服饰 & 配饰
            ['category_name' => '女装服饰'],
            ['category_name' => '男装服饰'],
            ['category_name' => '女士包'],
            ['category_name' => '男士包与钱包'],
            ['category_name' => '时尚配饰'],

            // 鞋类
            ['category_name' => '女鞋'],
            ['category_name' => '男鞋'],

            // 保健与美容保养
            ['category_name' => '保健与美容保养'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
