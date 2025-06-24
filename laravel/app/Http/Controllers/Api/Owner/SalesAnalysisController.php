<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Sale_Item;//追加

class SalesAnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //クエリパラメータ：月か一日か
        $type  = $request->query('type','monthly');//デフォルトはmonthly
        //フォーマットを選択：
        $format = $type === 'dally'? '%Y-%m-%d' :'&Y-%m';

        //売上データを日別か月別で集計
        $salasData = Sale_Item::select(
            //SQLの関数を直接使う（SUM()やDATE_FORMAT()
            DB::raw("DATE_FORMAT(sales.created.at,'$format') as date"),
            DB::raw("SUM(purchase_amount) as total_sales_amount"),
            DB::raw("SUM(purchase_num) as total_item_sold")
        )
        //sales_items と sales を結合（purchase日時を使うため）
        ->join('sales','sales_items.sale_id','=','sales.id')
        //指定した日や月でまとめる
        ->groupBy('date')
        // 新しい日付が上に来るように並べる
        ->orderBy('date','desc')
        ->get();

        return response()->json([
            'data' => $salasData
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
