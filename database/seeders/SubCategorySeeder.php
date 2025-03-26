<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SubCategory;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [
            // 保健与美容保养
            ['subcategory_name' => '香水'],
            ['subcategory_name' => '化妆品'],
            ['subcategory_name' => '护肤品'],
            ['subcategory_name' => '隐形眼镜'],

            // 女士包 & 男士包与钱包
            ['subcategory_name' => '背包'],             // 通用款
            ['subcategory_name' => '男士背包'],         // 男士专属
            ['subcategory_name' => '手提包'],           // 适合正式/日常
            ['subcategory_name' => '托特包'],           // 容量大，适合通勤
            ['subcategory_name' => '单肩包'],           // 适合日常休闲
            ['subcategory_name' => '斜肩包'],           // 适合轻便出行
            ['subcategory_name' => '邮差包'],           // 适合商务/复古风
            ['subcategory_name' => '腰包'],             // 运动或潮流风格
            ['subcategory_name' => '手拿包'],           // 适合晚宴/正式场合
            ['subcategory_name' => '钱包与小包'],       // 小型收纳
            ['subcategory_name' => '男士钱包'],         // 男士专属
            ['subcategory_name' => '包包配件'],         // 配件分类

            // 男装 & 女装服饰
            ['subcategory_name' => '上衣'],             // T恤、衬衫等
            ['subcategory_name' => '外搭'],             // 外套、夹克等
            ['subcategory_name' => '长裤与短裤'],       // 牛仔裤、休闲裤等
            ['subcategory_name' => '连身裤'],           // 连体裤、工装裤等
            ['subcategory_name' => '套装'],             // 统一搭配的服装
            ['subcategory_name' => '连衣裙'],           // 女士专属
            ['subcategory_name' => '半身裙'],           // 女士专属
            ['subcategory_name' => '大码女装'],         // 女士专属
            ['subcategory_name' => '孕妇装'],           // 特殊需求
            ['subcategory_name' => '运动与沙滩装'],     // 运动服、泳装等
            ['subcategory_name' => '传统服装'],         // 旗袍、马来装等
            ['subcategory_name' => '睡衣'],             // 居家服、睡衣
            ['subcategory_name' => '内衣裤'],           // 内衣、内裤、胸罩等
            ['subcategory_name' => '袜子'],             // 额外配件

            // 男鞋 & 女鞋
            ['subcategory_name' => '鞋子'],             // 适用于所有鞋类的通用分类
            ['subcategory_name' => '正式鞋'],           // 适用于正式场合（皮鞋、正装鞋等）
            ['subcategory_name' => '高跟鞋'],           // 独立列出高跟鞋，方便筛选
            ['subcategory_name' => '休闲鞋'],           // 适合日常穿搭（乐福鞋、无带鞋、平底鞋等）
            ['subcategory_name' => '球鞋'],             // 运动风格（运动鞋、球鞋等）
            ['subcategory_name' => '靴子'],             // 专门的靴子分类
            ['subcategory_name' => '凉鞋与拖鞋'],       // 适合轻便穿搭（平底凉鞋、拖鞋等）
            ['subcategory_name' => '鞋子保养与配件'],   // 含鞋垫、鞋油、鞋带等

            // 时尚配饰
            ['subcategory_name' => '腰带'],
            ['subcategory_name' => '眼镜'],
            ['subcategory_name' => '发饰'],
            ['subcategory_name' => '帽子'],
            ['subcategory_name' => '首饰'],
            ['subcategory_name' => '手表'],

            // 电脑与配件
            ['subcategory_name' => '打印机与投影机'],
            ['subcategory_name' => '数据储存与硬盘'],
            ['subcategory_name' => '笔记本电脑包'],

            // 相机与无人机
            ['subcategory_name' => 'DSLR 数码单反相机'],
            ['subcategory_name' => '无反光镜可换镜头相机'],
            ['subcategory_name' => '全自动数码相机'],
            ['subcategory_name' => '拍立得相机'],
            ['subcategory_name' => '无人机与运动相机'],
            ['subcategory_name' => '镜头'],
            ['subcategory_name' => '相机配件'],
            ['subcategory_name' => '相机包'],
        ];

        foreach ($subcategories as $subcategory) {
            SubCategory::create($subcategory);
        }
    }
}
