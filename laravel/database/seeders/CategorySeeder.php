<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //カテゴリテーブル
        DB::table('categories')->insert([[
            'name' => 'ぬいぐるみ',
            'sort_order' => 44,
        ],[
            'name' => 'ボードゲーム',
            'sort_order' => 60,
        ],[
            'name' => 'カード',
            'sort_order' => 60,
        ],[
            'name' => 'ブロック',
            'sort_order' => 75,
        ],[
            'name' => 'アクションフィギュア',
            'sort_order' => 65,
        ],[
            'name' => '知育',
            'sort_order' => 80,
        ],[
            'name' => '電子',
            'sort_order' => 72,
        ],[
            'name' => '人形とハウス',
            'sort_order' => 68,
        ],[
            'name' => '乗り物',
            'sort_order' => 10,
        ],[
            'name' => 'ゲームソフト',
            'sort_order' => 80,
        ],]);
    }
}
