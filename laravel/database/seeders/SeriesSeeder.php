<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //シリーズテーブル
        DB::table('series')->insert([[
            'name' => 'エゴブロック',
            'sort_order' => 80,
        ],[
            'name' => 'バトルドーム',
            'sort_order' => 20,
        ],[
            'name' => 'カンパンマン',
            'sort_order' => 75,
        ],[
            'name' => '決めつけの刃',
            'sort_order' => 60,
        ],[
            'name' => 'フラレール',
            'sort_order' => 50,
        ],[
            'name' => 'ドミカ',
            'sort_order' => 50,
        ],[
            'name' => 'ボケモン',
            'sort_order' => 85,
        ],[
            'name' => 'マジンクラフト',
            'sort_order' => 60,
        ],[
            'name' => 'イキリンジャー',
            'sort_order' => 3,
        ],[
            'name' => 'さばいてブリキュア',
            'sort_order' => 10,
        ],]);
    }
}
