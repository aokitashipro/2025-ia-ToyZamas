<?php

namespace App\Services;
use App\Models\Toy;

class SortService
{
    public function sort($request)
    {
        $result = $request->query('sort'); //sortの値があればtrue,無ければfalse
        if ($result)
        {
            //予約対象商品なら
            if($request->sort == 1) {
                $toys = Toy::where('is_reserve', true)->get();
                // return ToyListResource::collection($toys);
            }
        } else {

            if($request->category_id != 0 && $request->series_id != 0)
            {
                $toys = Toy::with(['category', 'series'])
                ->select('id', 'name', 'price', 'category_id', 'series_id', 'stock', 'is_selling', 'is_reserve', 'created_at')
                ->where('category_id',$request->category_id)
                ->where('series_id',$request->series_id)
                ->get();
            } else if($request->category_id != 0 && $request->series_id == 0){
                $toys = Toy::with(['category', 'series'])
                ->select('id', 'name', 'price', 'category_id', 'series_id', 'stock', 'is_selling', 'is_reserve', 'created_at')
                ->where('category_id',$request->category_id)
                ->get();
            } else if($request->category_id == 0 && $request->series_id != 0){
                $toys = Toy::with(['category', 'series'])
                ->select('id', 'name', 'price', 'category_id', 'series_id', 'stock', 'is_selling', 'is_reserve', 'created_at')
                ->where('series_id',$request->series_id)
                ->get();
            } else{
                //ランダムに１０こ取得
                $toys = Toy::inRandomOrder()->limit(10)->get();
            }
        }
        // dd($toys);
        return $toys;
    }
}
